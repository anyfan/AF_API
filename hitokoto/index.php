<?php
header("Content-type: text/html; charset=utf-8"); //设置编码 utf-8 
include 'data.php';

$cat = $_GET['cat'];
$len = (int)$_GET['len'];
$charset = $_GET['charset'];
$encode = $_GET['encode'];

 
if (($cat != 'h')&&($cat != 'd')&&($cat != 'j')&&($cat != 'y')) {
    $type = 0;
}else {
    $type = $cat;
}
$db = new db;
print_r($db->get($type, $len)['hitokoto']);






//编码判断，用于输出相应的响应头部编码
// if (isset($_GET['charset']) && !empty($_GET['charset'])) {
//     $charset = $_GET['charset'];
//     if (strcasecmp($charset,"gbk") == 0 ) {
//         $content = mb_convert_encoding($content,'gbk', 'utf-8');
//     }
// } else {
//     $charset = 'utf-8';
// }

//格式化判断，输出js或纯文本
// if ($_GET['encode'] === 'js') {
//     echo "function hitokoto(){document.write('" . $content ."');}";
// } else {
//     echo $content;
// }
