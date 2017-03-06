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

    $result = exec("curl " . $request


        . ' -H "Accept:application/xml"'


        . ' -H "X-gr-AuthDate:' . $headers['X-gr-AuthDate'] . '"'


        . ' -H "Authorization:' . $headers['Authorization'] . '"'

    );

    $answer_code = json_decode($result)->code;
    if ($answer_code != 200){
        throw new \Exception("Fehler bei der Anfrage! \n Antwort TAM: ".json_decode($result)->body."");
    }

    return json_decode($result)->body;
}

   function getUniqueId($object) {
        return str_replace(":","",$object->Date.$object->StartTime.$object->Location.$object->ID);
    }

