<?php

$base = 'http://127.0.0.1:8001';
$cookieFile = __DIR__.'/../storage/framework/testing/trader_cookies.txt';
@mkdir(dirname($cookieFile), 0777, true);

$ch = curl_init($base.'/trader/login');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
]);
$res = curl_exec($ch);
if ($res === false) {
    fwrite(STDERR, curl_error($ch).PHP_EOL);
    exit(1);
}
$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headersRaw = substr($res, 0, $headerSize);
$body = substr($res, $headerSize);
preg_match('/name="_token" value="([^"]+)"/', $body, $m);
$token = $m[1] ?? '';
echo "GET_HEADERS_BEGIN\n";
echo $headersRaw;
echo "GET_HEADERS_END\n";
echo 'token_len='.strlen($token).PHP_EOL;
curl_close($ch);

$post = curl_init($base.'/trader/login');
curl_setopt_array($post, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'email' => 'trader@demo.com',
        'password' => 'password123',
        '_token' => $token,
    ]),
    CURLOPT_COOKIEJAR => $cookieFile,
    CURLOPT_COOKIEFILE => $cookieFile,
    CURLOPT_FOLLOWLOCATION => false,
]);
$res2 = curl_exec($post);
if ($res2 === false) {
    fwrite(STDERR, curl_error($post).PHP_EOL);
    exit(1);
}
$code = curl_getinfo($post, CURLINFO_HTTP_CODE);
$headerSize2 = curl_getinfo($post, CURLINFO_HEADER_SIZE);
$headersRaw2 = substr($res2, 0, $headerSize2);
$loc = curl_getinfo($post, CURLINFO_REDIRECT_URL);
echo "POST_HEADERS_BEGIN\n";
echo $headersRaw2;
echo "POST_HEADERS_END\n";
echo 'code='.$code.PHP_EOL;
echo 'location='.($loc ?: '').PHP_EOL;
curl_close($post);
