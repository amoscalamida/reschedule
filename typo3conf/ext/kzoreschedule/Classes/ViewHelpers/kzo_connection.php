<?php

function makeKZORequest($param,$reference = "loginname",$list) {

    $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['kzoreschedule']);
    $time = time();
    $apiKey = hash("sha256",$extensionConfiguration['kzo_api.']['key'].$time);
    $url = $extensionConfiguration['kzo_api.']['uri'];
    $controller = $extensionConfiguration['kzo_api.']['controller'];

    $request = "$url$controller?requestParam=$param&reference=$reference&apiKey=$apiKey&list=$list&tt=$time";

    $cSession = curl_init();
    curl_setopt($cSession,CURLOPT_URL,$request);
    curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($cSession,CURLOPT_HEADER, false);
    if(! $result = curl_exec($cSession))
    {
        $curl_error = curl_error($cSession);
    }
    if ($extensionConfiguration['api_debug.']['showDebugRequestTime']) {
        $info = curl_getinfo($cSession, CURLINFO_TOTAL_TIME);
        echo("<span style='margin-right:10px;'>" . (($info < 4) ? ($info < 1) ? "<span class='label-success label'>Reaktionszeit KZO: $info s</span>" : "<span class='label label-warning'>Reaktionszeit KZO: $info s</span>" : "<span class='label-danger label'>Reaktionszeit KZO: $info s</span>") . "</span>");
    }
    curl_close($cSession);
    $answer_code = json_decode($result)->code;
    if ($answer_code != 200){
        throw new \Exception("Fehler bei der Anfrage! \n Antwort KZO: ".((json_decode($result)->message != "")?json_decode($result)->message:$curl_error)."");
    }

    return json_decode($result)->body;

}