<?php



$consumer_key = '    ';
$consumer_secret = '    ';
$oauth_token = '    ';
$oauth_token_secret = '    ';
 
$url = 'https://stream.twitter.com/1.1/statuses/sample.json';
 
$method = 'GET';
 
$post_parameters = array(
);
$get_parameters = array(
);

$oauth_parameters = array(
    'oauth_consumer_key' => $consumer_key,
    'oauth_nonce' => microtime(),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => time(),
    'oauth_token' => $oauth_token,
    'oauth_version' => '1.0',
);
 
$a = array_merge($oauth_parameters, $post_parameters, $get_parameters);

ksort($a);

$base_string = implode('&', array(
    rawurlencode($method),
    rawurlencode($url),
    rawurlencode(http_build_query($a, '', '&', PHP_QUERY_RFC3986))
));

$key = implode('&', array(rawurlencode($consumer_secret), rawurlencode($oauth_token_secret)));

$oauth_parameters['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_string, $key, true));
 
 
$fp = fsockopen("ssl://stream.twitter.com", 443);
if ($fp) {
    fwrite($fp, "GET " . $url  . " HTTP/1.1\r\n"
                . "Host: stream.twitter.com\r\n"
                . 'Authorization: OAuth ' . http_build_query($oauth_parameters, '', ',', PHP_QUERY_RFC3986) . "\r\n"
                . "\r\n");
    while (!feof($fp)) {
        echo fgets($fp);
    }
    fclose($fp);
}





?>


