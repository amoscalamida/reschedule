<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;
include_once "tam_connection.php";
class AssistantSearchViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * provides user with movement suggestions
     *
     * @param string $inputClass
     * @param string $inputDate
     * @param string $inputSubject
     * @return string - the property's value
     */

    public function render($inputClass,$inputDate,$inputSubject)
    {


        $output = "";


        function generateLayout($course) {
            global $movableLessons;
            return "<div class='panel panel-default' id='".getUniqueId($course)."'><div class='panel-heading'>".(($movableLessons[$course->Subject]!="")?$movableLessons[$course->Subject]:$course->Subject)." (".$course->Date." @ ".$course->StartTime." - ".$course->EndTime.")"."</div></div>";
        }

        function checkTeacherLesson($teacher,$fallout) {

            $getTeacherSchedule = array('WHERE' => array("Date"=> $fallout->Date , "StartTime"=> $fallout->StartTime,"Teacher" => $teacher));
            return makeRequest($getTeacherSchedule);


        }

        $movableLessons = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten","BG1" => "Bildnerisches Gestalten 1","BG2" => "Bildnerisches Gestalten 2", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers");

        $optimalTimes = array("08:25:00","07:30:00","11:20:00","13:20:00","16:05:00","16:55:00");

        $class = $inputClass;
        $date = date("Y-m-d",strtotime($inputDate));
        $startTime = date("H:i",strtotime($inputDate));
        $subject = $inputSubject;


        // FALLOUTS //

        if ($subject!=""){
            $getFallout = array('WHERE' => array("Class" => $class,"Date" => $date,"StartTime" =>$startTime,"Subject" => $subject), 'ORDER' => "Location");
        } else {
            $getFallout = array('WHERE' => array("Class" => $class,"Date" => $date,"StartTime" =>$startTime), 'ORDER' => "Location");
        }
        $fallouts = makeRequest($getFallout);

        $selectedFallout = $fallouts[0];
        $output .= ( "<h1>Ausfall:</h1><br>".generateLayout($selectedFallout));

// OPTIMALS //


        $baseDate = strtotime($date);

        $weekday = date("w",$baseDate);
        if ($weekday != 0 AND $weekday != 6) {
            if ($weekday != 1) {
                $weekStartDate = date('Y-m-d',strtotime("last Monday", $baseDate));
            } else {
                $weekStartDate = date('Y-m-d',$baseDate);

            }
            if ($weekday != 5) {
                $weekEndDate = date('Y-m-d',strtotime("next Friday", $baseDate));
            } else {
                $weekEndDate = date('Y-m-d',$baseDate);
            }
        } else {
            return "Date selection error!";
        }

        if (strtotime($weekStartDate)<strtotime("today") AND strtotime("today")<strtotime($weekEndDate)) {
            $weekStartDate = date('Y-m-d',strtotime("today"));
        }



        $getOptimals = array('WHERE' => array("Date" => 'BETWEEN "'.$weekStartDate.'" AND "'.$weekEndDate.'"', "Class" => $class), 'ORDER' => "Day");
        $optimal_candidates = makeRequest($getOptimals);
        $optimals = array();
        foreach ($optimal_candidates as $optimal_candidate) {

            if(
                array_key_exists($optimal_candidate->Subject,$movableLessons)
                AND
                in_array($optimal_candidate->StartTime,$optimalTimes)
            )
            {

                switch($optimal_candidate->StartTime) {

                    case "07:30:00":
                        $optimal_candidate->Priority = "1";
                        break;

                    case "08:25:00":
                        $optimal_candidate->Priority = "4";
                        break;

                    case "09:20:00":
                        $optimal_candidate->Priority = "5";
                        break;

                    case "11:20:00":
                        $optimal_candidate->Priority = "6";
                        break;

                    case "12:25:00":
                        $optimal_candidate->Priority = "7";
                        break;

                    case "13:20:00":
                        $optimal_candidate->Priority = "8";
                        break;

                    case "16:05:00":
                        $optimal_candidate->Priority = "3";
                        break;

                    case "16:55:00":
                        $optimal_candidate->Priority = "2";
                        break;

                }

                array_push($optimals,$optimal_candidate);
            }

        }

        $priorities = array();


        $output .= "<h1>Möglichkeiten</h1><br>";
        $output .= "<div id='possible-container' class='panel-group col-md-12'>";
        foreach ($optimals as $key=>$optimal){

            if(count(checkTeacherLesson($optimal->Teacher,$selectedFallout)) == 0) {
                //echo generateTimeTable($optimal);
                array_push($priorities,array("ID" => getUniqueId($optimal),"Priority" => $optimal->Priority));

                $output .= "<div class='panel panel-default possible' id='".getUniqueId($optimal)."' data-sort='".$optimal->Priority."'><div class='panel-heading' data-toggle='collapse' href='#collapse".$key."'>".$movableLessons[$optimal->Subject]." (".date("D",strtotime($optimal->Date))." @ ".$optimal->StartTime." - ".$optimal->EndTime.") [".$optimal->Teacher." / ".$optimal->Location."]"."</div><div id='collapse".$key."' class='panel-collapse collapse'><div class='panel-body'><div class='table-holder'></div><form action='table_generator.php' method='POST' data-id='".$key."' class='tableForm'><input type='hidden' value='".getUniqueId($optimal)."' name='req_id'/><input type='hidden' value='".$baseDate."' name='date'/><input type='submit' value='Tabelle laden'/></form><img class='loader".$key."' src='loader.svg' width='50px' style='display:none'/></div></div></div>";
            }

        }
        $output .= "</div>";

        if (count($priorities)==0) {
            $output .= "<div class='panel panel-danger'><div class='panel-heading' >Es wurden keine möglichen Verschiebungen gefunden</div></div>";
        }else {
            foreach ($priorities as $key => $value) {
                $priority[$key]    = $value['Priority'];
            }

            array_multisort($priority, SORT_ASC, $priorities);

            $bestMatches = array();
            $i = 0;
            $lowestPriority = 0;
            foreach ($priorities as $match) {
                if ($i == 0) {
                    $lowestPriority = $match['Priority'];
                }
                if ($match['Priority'] == $lowestPriority) {
                    array_push($bestMatches, $match);
                } else {
                    break;
                }
                $i++;
            }

    }
        $x = 0;
        $output .= "<script type='text/javascript'>";
        foreach ($bestMatches as $bestMatch) {
            $output .= " 
        var bestMatchId".$x." = '". $bestMatch["ID"]."';
        $('#'+bestMatchId".$x.").addClass('panel-success');";
            $x++;
        }
        $output .= "</script>";

        return $output;
    }

}

?>