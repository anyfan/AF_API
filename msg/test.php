<?php
include('../config.php');
$corpid = $work_wechat_config['corpid'];
$corpsecret = $work_wechat_config['corpsecret'];


function geturl($url)
{
    $headerArray = array("Content-type:application/json;", "Accept:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output, true);
    return $output;
}


function posturl($url, $data)
{
    // $data  = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $data  = json_encode($data);
    // echo ($data);
    $headerArray = array("content-type: application/json; charset=UTF-8", "Accept:application/json");
    // $headerArray = array("content-type: application/json");
    $curl = curl_init();
    // curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36');
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    // curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    $output = curl_exec($curl);
    curl_close($curl);
    // return json_decode($output, true);
    return $output;
}

// $corpid = "ww9de07ffaf422ea14";
// $corpsecret = "2_v6oZwM8GZx5EOXHa5CpDbq39g2iOsTnQe3tNzi6lM";
$url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$corpid&corpsecret=$corpsecret";

// 获取access_token
$access_token = geturl($url)["access_token"];

// $url = "https://qyapi.weixin.qq.com/cgi-bin/linkedcorp/message/send?access_token=$access_token";
$url = "https://qyapi.weixin.qq.com/cgi-bin/message/send?access_token=$access_token&debug=1";

$content = "你好";

$data = array(
    "touser" => "af",
    "msgtype" => "text",
    "agentid" => 1000002,
    "text" => array(
        "content" => $content
    )
);
// $data  = json_encode($data);
echo (posturl($url, $data));
