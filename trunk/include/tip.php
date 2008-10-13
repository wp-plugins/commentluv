<?php
// include file for comemntluv to display tooltip for heart icon
// 6 Oct 08 - added another function for curl if fopen and filegetcontents fail
// 9 oct 08 - added last resort iframe html if fopen, file_get_contents and cURL fail
if(function_exists("curl_init")){
			//setup curl values
			$url=$_GET['url'];
			$curl=curl_init();
			curl_setopt($curl,CURLOPT_URL,"http://www.commentluv.com/commentluvinc/clplus_tooltip.php?url=".$url);
			curl_setopt($curl,CURLOPT_HEADER,0);
			curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);
			curl_setopt($curl,CURLOPT_TIMEOUT,7);
			$content=curl_exec($curl);

			if(!curl_error($curl)){
				$data=$content;
				curl_close($curl);
			} else {
				// can't do curl so echo out iframe html
				$data="<iframe frameborder=0 width=\"360\" height=\"400\" src=\"http://www.commentluv.com/commentluvinc/clplus_tooltip.php?url=".$_GET['url']."\"></iframe>";
			}
		} else {
			$data="<iframe frameborder=0 width=\"360\" height=\"400\" src=\"http://www.commentluv.com/commentluvinc/clplus_tooltip.php?url=".$_GET['url']."\"></iframe>";
		}

		echo $data;
?>