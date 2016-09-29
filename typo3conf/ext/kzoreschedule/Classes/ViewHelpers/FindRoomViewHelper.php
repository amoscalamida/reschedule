<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;

class FindRoomViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @param datetime $datetime The date and time of the Lesson
     * @param string $category The category string
     * @return array the List of rooms
     */

    public function render($datetime,$category) {

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


        $conditions = array('WHERE' => array(), 'ORDER' => 'Date');

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
        $json = json_decode($result)->body;



        $rooms = array();
        $Query = array("Date" => $datetime->format('Y-m-d'), "StartTime" => $datetime->format('H:i:s'));
        $result = array();
        $course_count = 0;

        foreach ($json as $course) {
            if (!in_array($course->Location,$rooms)) {
                array_push($rooms, $course->Location);
            }
        }

        foreach ($json as $course) {
            if ($course->Date == $Query["Date"] AND $course->StartTime == $Query["StartTime"])
            {
                if(($key = array_search($course->Location, $rooms)) !== false) {
                    unset($rooms[$key]);
                    $course_count++;
                }
            }
        }

        if ($course_count == 0) {
            return "Suche fehlgeschlagen: Für dieses Datum stehen (noch) keine Stundenpläne zur Verfügung. </br> ";
            die();
        }


        switch ($category) {

            case "G":
                if(substr($room, 0, 1) != 'C' && substr($room, 0, 1) != 'B' && substr($room, 0, 2) != 'TH' && substr($room, 0, 1) != 'Z' && substr($room, 0, 1) != 'P' && is_numeric(substr($room, 0, 1))){
                array_push($result,$room);
                }
                break;
            case "C":
                if(substr($room, 0, 1) == 'C'){
                 array_push($result,$room);
                }
                break;
            case "B":
               if(substr($room, 0, 1) == 'B'){
                array_push($result,$room);
               }
                break;
            case "T":
                if(substr($room, 0, 2) == 'TH') {
                array_push($result,$room);
                }
                break;
            case "P":
                if(substr($room, 0, 1) == 'P'){
                array_push($result,$room);
                }
                break;
            case "BG":
                if(substr($room, 0, 1) == 'Z'){
                array_push($result,$room);
                }
                break;
            case "S":
                if(!is_numeric(substr($room, 0, 1))){
                array_push($result,$room);
                }
                break;
        }


        foreach ($rooms as $room) {

            switch ($category) {

                case "G":
                    if(substr($room, 0, 1) != 'C' && substr($room, 0, 1) != 'B' && substr($room, 0, 2) != 'TH' && substr($room, 0, 1) != 'Z' && substr($room, 0, 1) != 'P' && is_numeric(substr($room, 0, 1))){
                        array_push($result,$room);
                    }
                    break;
                case "C":
                    if(substr($room, 0, 1) == 'C'){
                        array_push($result,$room);
                    }
                    break;
                case "B":
                    if(substr($room, 0, 1) == 'B'){
                        array_push($result,$room);
                    }
                    break;
                case "T":
                    if(substr($room, 0, 2) == 'TH') {
                        array_push($result,$room);
                    }
                    break;
                case "P":
                    if(substr($room, 0, 1) == 'P'){
                        array_push($result,$room);
                    }
                    break;
                case "Z":
                    if(substr($room, 0, 1) == 'Z'){
                        array_push($result,$room);
                    }
                    break;
                case "S":
                    if(substr($room, 0, 1) != 'C' && substr($room, 0, 1) != 'B' && substr($room, 0, 2) != 'TH' && substr($room, 0, 1) != 'Z' && substr($room, 0, 1) != 'P' && !is_numeric(substr($room, 0, 1))){
                        array_push($result,$room);
                    }
                    break;
            }
        }

        sort($result);
        $arr_length = count($result);
        $row_length = ceil($arr_length/3);
        if (!empty($result)) {
           $list = "<div class='row'>";
            for ($v = 0; $v <= 2; $v++){
                $list .= "<div class='col-xs-4'><div class='list-group'>";
                    for ($i = (0+($v*$row_length)); $i <= $row_length + ($v*$row_length)-1; $i++) {
                        if (isset($result[$i])){
                        $list .= "<a class='list-group-item room-select'>" . $result[$i] . "</a>";
                        }
                    }
                $list .= "</div></div>";
            }

            $list .= "</div>";
            return $list;
        }
        else {
            return "Keine freien Zimmer gefunden. </br> Bitte Einschränkung entfernen und erneut versuchen.";
        }


        }

    }

?>