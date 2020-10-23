<?php
    // pixiv_get.a-f.workers.dev代理pixiv.net
    $url="https://pixiv_get.a-f.workers.dev/ranking.php?mode=daily&content=illust&p=1&format=json";

	$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
    $ch = curl_init();
    // 获取header信息，检索出cookie
    curl_setopt($ch, CURLOPT_HEADER, 1);
    // 伪造ua,否则无法访问;
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
  
	// 解析COOKIE,一般设置第一个cookie就可以通过验证了。
    preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
	$cookie = explode(';', $matches[0][0])[0];
	$cookie =str_replace("Set-Cookie: ","",$cookie);

	curl_setopt($ch,CURLOPT_COOKIE,$cookie);
    // 	清除header显示信息
    curl_setopt($ch, CURLOPT_HEADER, 0);

	$content = curl_exec($ch);

	curl_close($ch);
    // 返回原始信息
    // echo($content);
    
    
    // JSON字符串强制转成PHP数组 
    $data = json_decode($content, true);
    // 获取第一个出现的url
    $date_no1=$data["contents"][0]["url"];
    //修改图片地址为反代域名
    // $date_no1 =str_replace("https://i.pximg.net/c/240x480/","https://pixiv.anyfan.top/",$date_no1);
    $date_no1 =str_replace("https://i.pximg.net/c/240x480/","https://pixiv.a-f.workers.dev/",$date_no1);
    
    echo($date_no1);

?>