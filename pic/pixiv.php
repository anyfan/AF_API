<?php

// pixiv_get.a-f.workers.dev代理pixiv.net
$url="https://pixiv_get.a-f.workers.dev/ranking.php?mode=daily&content=illust&p=1&format=json";
$cache_file='pixiv_no1_cache.json';
// $url = "aaaaa";

function get_url($url)
{
    $useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
    $ch = curl_init();
    // 伪造ua,否则无法访问;
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    // 获取header信息，检索出cookie
    // curl_setopt($ch, CURLOPT_HEADER, 1);
    // 解析COOKIE,一般设置第一个cookie就可以通过验证了。
    // preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
    // $cookie = explode(';', $matches[0][0])[0];
    // $cookie =str_replace("Set-Cookie: ","",$cookie);
    // curl_setopt($ch,CURLOPT_COOKIE,$cookie);
    // 	清除header显示信息
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}

function update_catch($url, $cache_file)
{
    $data = get_url($url);
    if ($data!=null) {
        $fp = fopen($cache_file, 'w+');
        fwrite($fp, $data);
        fclose($fp);
    }
}

function get_no1($data)
{
    // $data = file_get_contents($cache_file);
    // JSON字符串强制转成PHP数组 
    $data = json_decode($data, true);
    // 获取第一个出现的url
    $date_no1 = $data["contents"][0]["url"];
    //修改图片地址为反代域名
    $date_no1 = str_replace("https://i.pximg.net/c/240x480/", "https://pixiv.a-f.workers.dev/", $date_no1);
    return $date_no1;
}

if (file_exists($cache_file)) {
    $catch_time = strtotime(date('Y-m-d H:i:s')) - filemtime($cache_file);
    if ($catch_time>600) {
        update_catch($url, $cache_file);
    } 
} else {
    update_catch($url, $cache_file);
}
$data = file_get_contents($cache_file);
$no1_src = get_no1($data);
if ($no1_src == '') {
    // echo ('$no1_src');
    update_catch($url, $cache_file);
} else {
    echo($no1_src);
}
