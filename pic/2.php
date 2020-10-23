<?php
$json_string = file_get_contents('1.json'); 
$data = json_decode($json_string, true);


$date_no1=$data["contents"][0]["url"];
// https://i.pximg.net/c/240x480/img-master/img/2020/08/05/00/00/07/83451350_p0_master1200.jpg

// https://pixiv.anyfan.top/img-master/img/2020/08/05/00/00/07/83451350_p0_master1200.jpg


$date_no1 =str_replace("https://i.pximg.net/c/240x480/","https://pixiv.anyfan.top/",$date_no1);



echo($date_no1);



