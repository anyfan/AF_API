<?php
require_once 'pwd.php';

require_once 'date.php';
$fi = new Storage();
// print_r($fi->get()) ;
$secret = $fi->find('anyfan');


$ga = new Authenticator();
// $secret = $ga->createSecret();
echo "密钥: " . $secret . "<br><br>";

$qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
echo "QR-Code: " . $qrCodeUrl . "<br><br>";

$oneCode = $ga->getCode($secret);
echo "Checking Code '$oneCode' and Secret '$secret':";

$checkResult = $ga->verifyCode($secret, $oneCode);    // 2 = 2*30sec clock tolerance
if ($checkResult) {
    echo 'OK<br>';
} else {
    echo 'FAILED<br>';
}
