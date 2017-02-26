<?php

include_once("headers.php");

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
        exit("Fehler bei der Anfrage!<br>Antwort TAM: <code>".json_decode($result)->body)."</code>";
    }

    return json_decode($result)->body;
}

   function getUniqueId($object) {
        return str_replace(":","",$object->Date.$object->StartTime.$object->Location.$object->ID);
    }

