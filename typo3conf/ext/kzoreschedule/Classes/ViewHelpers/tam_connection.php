<?php

include_once("Headers.php");

function makeRequest($conditions) {
$mod = base64_encode(json_encode($conditions));
$user       = 'rest-timetable';
$pwd        = 'a46vQJzsY9YwVhKCxjUD';
$controller = 'data/source/timetable';
$school     = 'kzo';

$headers = generateHeaders($user, $pwd, 'gr001');
$request = "https://api.tam.ch/$school/$controller?mod=$mod";

    $result = exec("curl ".$request


	.' -H "Accept:application/xml"'


	.' -H "X-gr-AuthDate:'.$headers['X-gr-AuthDate'].'"'


	.' -H "Authorization:'.$headers['Authorization'].'"'

);
    return json_decode($result)->body;
}

   function getUniqueId($object) {
        return str_replace(":","",$object->Date.$object->StartTime.$object->Location.$object->ID);
    }

