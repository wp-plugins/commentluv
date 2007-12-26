<?php /*
Plugin Name: Commentluv
Plugin URI: http://www.fiddyp.co.uk/commentluv-wordpress-plugin/
Description: Plugin to show a link to the last post from the commenters blog in their comment. Just activate and it's ready. Currently works with wordpress, blogspot, typepad and blogs that have a feedburner feed link somewhere on their page.
Version: 0.93
Author: Andy Bailey
Author URI: http://www.fiddyp.co.uk/

updates:
0.93 - use wp internal function to parse feed and improve find feed location
0.92 - update comments
0.91 - fix: compatibility with some other comment enhancing plugins so the link isn't repeated
0.9 - now wont output emptry string if no last post found (blogspot blog with own domain)
0.8 - now prevents parsing on a trackback, pingback or admin comment
0.71 - trying to prevent showing last post on trackbacks
0.7 - prevented admin from having feed parsed when replying to comments
(thanks thesmocklady.com/blog/)
0.6 - fixed problem where it wouldn't find the feed if the blog was in a subdirectory
(found by http://thesmocklady.com/blog/)
0.51 - fiddled with timeout. Some feeds were not showing due to it taking too long to load the
commenter's page. Testing done by thedivanetwork.com and lalla-mira.com/. Thanks!
0.5 - typepad,blogspot,wordpress all working, tries to find a feedburner feed in the authors page
if it's not found in a default location. pretty robust, can now work with script links to
feedburner.
0.4 - try and find users feed if they don't have a default wordpress/blogger/typepad blog
0.3 - works with typepad blogs feed, default and feedburner
0.2 - works with feedburner feed for wordpress and blogger default location
0.1 - works with wordpress default feed at default location


*/


// text between function (to make it easy to parse different types of feeds and streams)
function LL_TextBetween($s1,$s2,$s){
	$s1 = strtolower($s1);
	$s2 = strtolower($s2);
	$L1 = strlen($s1);
	$scheck = strtolower($s);
	if($L1>0){$pos1 = strpos($scheck,$s1);} else {$pos1=0;}
	if($pos1 !== false){
		if($s2 == '') return substr($s,$pos1+$L1);
		$pos2 = strpos(substr($scheck,$pos1+$L1),$s2);
		if($pos2!==false) return substr($s,$pos1+$L1,$pos2);
	}
	return '';
}

// find feedburner feed function (parses a users page for a feed link)
function findfeedburner($page_url){
	// can't open default wordpress feed, use curl to parse users page for a relative link feed
	if(function_exists(curl_init)) {
		$ch=curl_init();
		$timeout = 10; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $page_url );
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data=curl_exec($ch);
		curl_close($ch);
		$lines=explode("\n",$data);
		// look for feedburner url
		foreach($lines as $line){
			if(strstr($line,"title=\"RSS")){
				$pos=strpos($line,"title=\"RSS");
				$cut=substr($line,$pos+5);					
				$feed_url=LL_TextBetween("href=\"","\"",$cut);
				break;
			}
		}
	}
	return $feed_url;
}


// hooks, call comment_luv function just before comment is posted . gets passed array of comment fields
// hooks, call add_text when comment form is shown, gets passed id of post
add_filter('preprocess_comment','comment_luv');
add_action('comment_form','add_text');

// function to add text to bottom of form field
function add_text($id){
	echo "<br><em>This blog uses the <a href='http://www.fiddyp.co.uk/commentluv-wordpress-plugin'>CommentLuv plugin</a> which will try and parse your sites feed and display a link to your last post, please be patient while it tries to find it for you.</em>";
	return $id; // need to return what we got sent
}

// this is the magic part.
// function to parse the users feed and add a link to last post after the rest of the comment content
function comment_luv($comment_data){
	// don't parse for admin posting comment reply or trackback
	get_currentuserinfo() ;
	global $user_level;
	if ($user_level > 7 || $comment_data['comment_type'] == 'pingback' || $incoming_comment['comment_type'] == 'trackback' || strstr($comment_data['comment_content'],"'s last blog post")) {
		return $comment_data;
	}
	// use wp internal rss.php function (wp 2.1+ only)
	include_once(ABSPATH . WPINC . '/rss.php');
	// get url of comment authors site
	$author_url=$comment_data['comment_author_url'];

	// get url of author and determine type of blog platform for feed parsing
	$bareurl=explode("/",$author_url);
	if(strstr($author_url,"blogspot")){					// blogspot blog
		$feed_url=$bareurl[2]."/feeds/posts/default/";
	}elseif(strstr($author_url,"typepad")){				// typepad blog
		if($bareurl[3]){									// check for subdirectory in url
			$feed_url=$bareurl[2]."/".$bareurl[3]."/atom.xml";
		} else {											// doesn't have subdirectory
			$feed_url=$bareurl[2]."/atom.xml";
		}
	}else {												// must be wordpress or own domain blog
		if(!$bareurl[3]){ 			// check if blog is in subdirectory of domain
			$feed_url=$bareurl[2]."/feed/"; // use normal
// debug
//$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (wp_norm) ',strlen($comment_data['comment_content']),0);

		} else {
			$feed_url=$bareurl[2]."/".$bareurl[3]."/feed/"; // add the subdirectory
// debug
//$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (wp_sub) ',strlen($comment_data['comment_content']),0);

		}
	}

	// parse the authors url for feed
	if ($author_url){								// only do it if there is a url to parse
		$rss=fetch_rss("http://$feed_url"); 		// open authors feed and store as array
		if(!$rss){							// if feed not found, try and parse users page
			
// debug
//$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (curl) ',strlen($comment_data['comment_content']),0);
			$rss=fetch_rss(findfeedburner($author_url));// for feedburner feed
		}

		// check to see if it found a feed
		if (!$rss){
			
// debug
//$comment_data['comment_content']=substr_replace($comment_data['comment_content'], ' (notfound) ',strlen($comment_data['comment_content']),0);
			return $comment_data;
			//exit gracefully if no feed found
		}
		// step through feed for last post title and link
		$items= array_slice($rss->items,0,1);
		foreach($items as $item){
			$feed_title=$item['title'];
			$feed_post=$item['link'];
		}
		if($feed_title && $feed_post){	// only output if last post found
			// now need to insert what we got into the comment content
			$author_excerpt="\n\n<em>".$comment_data['comment_author']."'s last blog post..</em><a href='$feed_post'>$feed_title</a>";
			$comment_data['comment_content']=substr_replace($comment_data['comment_content'], $author_excerpt,strlen($comment_data['comment_content']),0);
		}
	}
	return $comment_data; // return what we got sent or modified version if feed found
}
?>