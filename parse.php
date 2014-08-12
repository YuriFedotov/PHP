<?php
include_once "form.php";
ini_set('max_execution_time', 600); //установить на 10 минут выполнение скрипта

$URL =     $_POST['link_rss'];
$tag_in = $_POST['tag_in'];
$tag_out = $_POST['tag_out'];
$page =   $_POST['page'];
$button =  $_POST['button'];

$link_host = domen_url($URL);

if(!empty($URL) && !empty($tag_in) && !empty($tag_out) && !empty($page)){

   echo "Парсинг запущен!";
   $content = file_get_contents($URL);

   $header = "<table align=center width=\"890\" bgcolor = '#fffff9' border = 1><tr><td>";
   $footer = "</td></tr></table>";
 
   preg_match_all("/title>[^>]+>/",$content, $title);
   preg_match_all("/link>[^>]+>/",$content, $link);
   preg_match_all("/description>[^>]+>/",$content, $description);
 
   $count = count($title[0]) - 1;

   if(!is_dir("page/".$page)){mkdir("page/".$page);}
  
   $fp = fopen("page/kod.txt", 'w+');
 
   for($i = 2; $i <= $count; $i++){
   
   if(!is_dir("page/".$page."/".$i)){mkdir("page/".$page."/".$i);} 
   
   echo "<h3>" .trim(substr($title[0][$i],6, -8)). "</h3>";
   echo "<p>"  .trim(substr($link[0][$i], 5, -7)). "</p>";
   echo "<h4>" .trim(substr($description[0][$i],12, -14)). "</h4>";
   echo "<hr>";
  
   $isprav_amp2 = str_replace("&amp;","&", substr($link[0][$i], 5, -7));
   
   $site_content = file_get_contents($isprav_amp2);
   
   
   
   $position2 = strpos($site_content, $tag_in);
   $site_content = substr($site_content, $position2);
   $position2 = strpos($site_content, $tag_out);
   $site_content = substr($site_content,0, $position2);
   $site_content = $header.$site_content.$footer;
   
   
   $all_img = get_images($site_content);
 
   $count_img = $all_img[1];
   $new_content = array();
   
   for($num = 0; $num < $count_img; $num++){
   
   $new_content[] = str_replace($all_img[0][$num], $i."/img_".$i."_".$num.get_extension($all_img[0][$num]), $site_content);
   
   if(valide_protokol($all_img[0][$num]) === FALSE){
   echo $link_host.$all_img[0][$num];
   $image = file_get_contents($link_host.$all_img[0][$num]);
   } else {
   echo $all_img[0][$num];
   $image = file_get_contents($all_img[0][$num]);
   }
    
    $fp3 = fopen("page/".$page."/".$i."/img_".$i."_".$num.get_extension($all_img[0][$num]), 'w+');
	fwrite($fp3, $image);
 }
    fclose($fp3);
	
   $fp2 = fopen("page/".$page."/page_".$i.".html", 'w+');
   foreach($new_content as $value){
   fwrite($fp2, $value);
   }
   fclose($fp2);
   $isprav_amp = str_replace("&amp;","&", substr($link[0][$i], 5, -7)); 
   fwrite($fp, substr($title[0][$i],6, -8)."::".$isprav_amp."::".substr($description[0][$i],12, -14)."::\r\n");
}
   fclose($fp);
    
}

else{

if(!empty($button)){
echo $table_error;
}
else echo $table;
}

function get_images($file){
preg_match_all('/(<img)\s (src="([a-zA-Z0-9\-\.;:\/\?&=_|\r|\n]{1,})")/isxmU' , $file, $patterns);
$res = array();
array_push($res, $patterns[3]);
array_push($res, count($patterns[3]));
return $res;
}


function get_extension($type_file){
  preg_match('/\.(jpg|jpeg|gif|png)/', $type_file, $result);
  
  if($result[1] ==     'jpg')     {return $result[0];}
  elseif($result[1] == 'jpeg'){return $result[0];}
  elseif($result[1] == 'gif') {return $result[0];}
  elseif($result[1] == 'png') {return $result[0];}
  
return $result;
}

function valide_protokol($url){
$count = strlen($url) - 7;
$result = substr($url, 0, -($count));
if($result != 'http://') {return FALSE;}
else {return TRUE;}
}

function domen_url($url){
preg_match('|(http://)([^/]*)/{0,1}(.*)|', $url, $url_res); 
return "http://".$url_res[2];
}
