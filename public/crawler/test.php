<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '-1');

include('simple_html_dom.php');

$domain = 'heartroofingflorida.com';
$limit = 50;
$start = 0;



$targetUrl = 'https://www.google.com/search?client=ms-google-coop&q=site:'.$domain.'&cx=e670fa3861a3fdac5&start='.$start.'&num='.$limit.'&safe=high'; 
$html = file_get_html($targetUrl);
$links = $list = array();
$i = 1;
foreach($html->find('a[href*="/url"]') as $a) {
    $links = $a->href;
    if (strpos($links,'google.com') !== false) {
    }else{
        $links = parse_url($links,PHP_URL_QUERY);
        $links =  str_replace("q=","",$links);    
        $url = strtok($links, '&');
        $list[] = $url;
            
    }
    $i++;
}
echo json_encode($list,true);