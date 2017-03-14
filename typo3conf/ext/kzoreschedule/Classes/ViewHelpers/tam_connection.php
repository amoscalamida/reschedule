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


function makeRequest($conditions) {
    $mod = base64_encode(json_encode($conditions));

    $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['kzoreschedule']);
    $user = $extensionConfiguration['tam_api.']['username'];
    $pwd = $extensionConfiguration['tam_api.']['password'];
    $uri = $extensionConfiguration['tam_api.']['uri'];
    $controller = $extensionConfiguration['tam_api.']['controller'];
    $school = 'kzo';

    $headers = generateHeaders($user, $pwd, 'gr001');

    $request = "$uri/$school/$controller?mod=$mod";

    $cSession = curl_init();
    curl_setopt($cSession,CURLOPT_URL,$request);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession, CURLOPT_HTTPHEADER, array("Accept:application/xml","X-gr-AuthDate:".$headers['X-gr-AuthDate'], "Authorization:".$headers['Authorization']));
    curl_setopt($cSession,CURLOPT_HEADER, false);
    if(! $result = curl_exec($cSession)) {
        $curl_error = curl_error($cSession);
    }
    if ($extensionConfiguration['api_debug.']['showDebugRequestTime']) {
        $info = curl_getinfo($cSession, CURLINFO_TOTAL_TIME);
        echo("<span style='margin-right:10px;'>" . (($info < 4) ? ($info < 1) ? "<span class='label-success label'>Reaktionszeit TAM: $info s</span>" : "<span class='label label-warning'>Reaktionszeit TAM: $info s</span>" : "<span class='label-danger label'>Reaktionszeit TAM: $info s</span>") . "</span>");
    }
    curl_close($cSession);
    $answer_code = json_decode($result)->code;
    if ($answer_code != 200) {
        throw new \Exception("Fehler bei der Anfrage! \n Antwort TAM: ".((json_decode($result)->body != "")?json_decode($result)->body:$curl_error)."");
    }

    return json_decode($result)->body;
}

   function getUniqueId($object) {
        return str_replace(":","",$object->Date.$object->StartTime.$object->Location.$object->ID);
    }

