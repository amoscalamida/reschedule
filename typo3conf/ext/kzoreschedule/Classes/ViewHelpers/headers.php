<?php
function generateHeaders($username, $password, $prefix, $hashAlgorithm = 'sha1')
{

    $rfc_1123_date = gmdate('D, d M Y H:i:s T', time());
    $xgrdate = utf8_encode($rfc_1123_date);
    $userPasswd = base64_encode(hash($hashAlgorithm, $password, true));

    $signature = base64_encode(hash_hmac($hashAlgorithm, $userPasswd, $xgrdate));
    $auth = $prefix . " " . base64_encode($username) . ":" . $signature;
    $headers = array(
        'X-gr-AuthDate' => $xgrdate,
        'Authorization' => $auth
    );


    return $headers;


}

