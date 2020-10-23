<?php
//curl模拟提交,绕过安全检查验证
$url="https://test.a-f.workers.dev/ranking.php?mode=daily&content=illust&p=1&format=json";


$post = array(
    'username' => '***',
    'passwd' => '***'
    // 'submit' => '现在登录'
);
//1.获取网站的cookie值
// $cookie = getCookie($api, $post, 1);
$cookie = getCookie($url);
// echo $cookie;
//2.携带cookie访问网址
$html=fetch_url($url,$cookie);
echo($html);



//3.检查json格式
// $data=jsonReplaceValue($html);
// $json = json_decode($data,1);
// echo $json;
// die;
 
/**
 * 实现登录并返回cookie值
 * @param $url
 * @param array $params
 * @param array $headers
 * @return bool|mixed|string
 */
function getCookie($url, $params = [], $headers = [])
{
	$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
    $timeout= 120;
	$httpInfo = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_URL, $url);
 
    $response = curl_exec($ch);
    if ($response === FALSE) {
        return false;
    }
 
    curl_close($ch);
    // 解析HTTP数据流
    list($header, $body) = explode("\r\n\r\n", $response);
	// 解析COOKIE
    preg_match_all("/set\-cookie:([^\r\n]*)/i", $header, $matches);
        //echo "<pre>";
	//var_dump($matches);
	//die;
	// 请求的时候headers带上cookie就可以了
	$cookie1 = explode(';', $matches[0][0])[0];
	$cookie1=str_replace("Set-Cookie: ","",$cookie1).";";
    $cookie2 = explode(';', $matches[0][1])[0];
	$cookie2=str_replace("Set-Cookie: ","",$cookie2);
    return $cookie1.$cookie2;
}
/**
 * 传入cookie开始访问请求
  * @param $url
 * @param $cookie
 * @return bool|string
 */
function fetch_url($url,$cookie) {
	$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
	$timeout= 120;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch,CURLOPT_COOKIE,$cookie);
	// 关闭https验证
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_ENCODING, "" );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt($ch, CURLOPT_AUTOREFERER, true );
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	$content = curl_exec($ch);
	if(curl_errno($ch))
	{
		echo 'Error:' . curl_error($ch);
	}
	else
	{
		return $content;        
	}
	curl_close($ch);
}
 
//json中给值增加上双引号
function jsonReplaceValue($str) 
{	
  $str=preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str);
  if(preg_match('/":\w/', $str)){
    $str =  preg_replace('/"([^"]+)":\s*(\d+)/', '"\1": "\2"', $str);
  }
  return $str;
 
}



 
