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



        $movableLessons = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten","BG1" => "Bildnerisches Gestalten 1","BG2" => "Bildnerisches Gestalten 2", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers");
        $lessonNames = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten","BG1" => "Bildnerisches Gestalten 1","BG2" => "Bildnerisches Gestalten 2","MU1" => "Musik 1","MU2" => "Musik 2", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers","+SP" => "FF Spanisch", "+IT" => "FF Italienisch","TM" => "Sport Herren","TF" => "Sport Damen", "K" => "Klassenstunde","FB" => "Französisch Besprechung", "DB" => "Deutsch Besprechung");

        $optimalTimes = array("08:25:00","07:30:00","11:20:00","13:20:00","16:05:00","16:55:00");

        $class = $inputClass;
        $date = date("Y-m-d",strtotime($inputDate));
        $startTime = date("H:i",strtotime($inputDate));
        $subject = $inputSubject;


        function checkTeacherLesson($teacher,$fallout) {

            $getTeacherSchedule = array('WHERE' => array("Date"=> $fallout->Date , "StartTime"=> $fallout->StartTime,"Teacher" => $teacher));
            return makeRequest($getTeacherSchedule);


        }

        function generateTimeTable($req_id,$date,$selectedFallout,$optimal_candidates) {


            $baseDate = strtotime($date);


            foreach($optimal_candidates as $optimal){

                if (getUniqueId($optimal) == $req_id) {
                    $optimal->Moved = true;
                    $timeParts = explode(":",$selectedFallout->StartTime);
                    $lessonNumber = $timeParts[0];
                    if (!isset(${"lesson".$lessonNumber.$selectedFallout->Day})) {
                        ${"lesson".$lessonNumber.$selectedFallout->Day} = array();
                    }
                    array_push(${"lesson".$lessonNumber.$selectedFallout->Day},$optimal);
                }
                else {
                    $optimal->Moved = false;
                    $timeParts = explode(":",$optimal->StartTime);
                    $lessonNumber = $timeParts[0];
                    if (!isset(${"lesson".$lessonNumber.$optimal->Day})) {
                        ${"lesson".$lessonNumber.$optimal->Day} = array();
                    }
                    array_push(${"lesson".$lessonNumber.$optimal->Day},$optimal);
                }
            }

            $lesson07 = array("07:30 Uhr",$lesson072,$lesson073,$lesson074,$lesson075,$lesson076);
            $lesson08 = array("08:25 Uhr",$lesson082,$lesson083,$lesson084,$lesson085,$lesson086);
            $lesson09 = array("09:20 Uhr",$lesson092,$lesson093,$lesson094,$lesson095,$lesson096);
            $lesson10 = array("10:25 Uhr",$lesson102,$lesson103,$lesson104,$lesson105,$lesson106);
            $lesson11 = array("11:20 Uhr",$lesson112,$lesson113,$lesson114,$lesson115,$lesson116);
            $lesson12 = array("12:25 Uhr",$lesson122,$lesson123,$lesson124,$lesson125,$lesson126);
            $lesson13 = array("13:20 Uhr",$lesson132,$lesson133,$lesson134,$lesson135,$lesson136);
            $lesson14 = array("14:15 Uhr",$lesson142,$lesson143,$lesson144,$lesson145,$lesson146);
            $lesson15 = array("15:10 Uhr",$lesson152,$lesson153,$lesson154,$lesson155,$lesson156);
            $lesson16 = array("16:05 Uhr",$lesson162,$lesson163,$lesson164,$lesson165,$lesson166);
            $lesson17 = array("16:55 Uhr",$lesson172,$lesson173,$lesson174,$lesson175,$lesson176);

            $lessonrows = array($lesson07,$lesson08,$lesson09,$lesson10,$lesson11,$lesson12,$lesson13,$lesson14,$lesson15,$lesson16,$lesson17);

            $table = "<div class='well'>";
            foreach ($lessonrows as $days){
                $table .= "<div class='row lessonrow'>";
                foreach ($days as $lessons) {

                    if (!is_array($lessons) && $lessons != "") {
                        $table .= "<div class='col-xs-1'>".$lessons."</div>";
                    } else {

                        $table .= "<div class='col-xs-2 dayXlesson'><div class='row text-center'>";
                        $tablecount = count($lessons);
                        if ($tablecount > 4) {
                            $filter = true;
                        }
                        $counter = 0;
                        $styling = "";
                        if (is_array($lessons)) {
                            foreach ($lessons as $course) {
                                if ($filter AND $counter > 3) {
                                    $styling = "display:none";
                                }
                                if(getUniqueId($course) != getUniqueId($selectedFallout)){
                                    $table .= "<div data-day=".$course->Day." style='".(($course->Moved)?'':$styling)."' class='course ".(($course->Moved)?'bg-success':'')."'>".(($lessonNames[$course->Subject]!="")?$lessonNames[$course->Subject]:$course->Subject).
                                        (($course->Moved)?" (".date("D",strtotime($course->Date))." ".$course->StartTime.")":"")."</div>";
                                }
                                else {
                                    $table   .= "<div data-day=".$course->Day." class='col-xs-$rowsize bg-danger'><del>".(($lessonNames[$course->Subject]!="")?$lessonNames[$course->Subject]:$course->Subject)."</del></div>";
                                }

                                $counter++;

                            }
                        }
                        $table .= "</div></div>";
                    }

                }
                $table .= "</div>";
            }
            $table .= "</div>";

            return $table;

        }

        // FALLOUTS //

        if ($subject!=""){
            $getFallout = array('WHERE' => array("Class" => $class,"Date" => $date,"StartTime" =>$startTime,"Subject" => $subject), 'ORDER' => "Location");
        } else {
            $getFallout = array('WHERE' => array("Class" => $class,"Date" => $date,"StartTime" =>$startTime), 'ORDER' => "Location");
        }
        $fallouts = makeRequest($getFallout);

        $selectedFallout = $fallouts[0];
        if ($selectedFallout == "") {
            return "<br><div class='panel panel-danger'><div class='panel-heading' ><b>FEHLER:</b> Es wurde keine ".(($inputSubject!='')?'Lektion ('.$movableLessons[$inputSubject].')':'Lektion')." zu diesem Zeitpunkt gefunden.".(($inputSubject!='')?'<br><small>TIPP: Wenn du das Feld "Schulfach" nicht ausfüllst, versucht der Assistent diese Information automatisch zu finden.</small>':'')."</div></div>";
        } else {
            $output .=  "<h1>Ausfall:</h1><br><div class='panel panel-default' id='".getUniqueId($selectedFallout)."'><div class='panel-heading'>".(($lessonNames[$selectedFallout->Subject]!="")?$lessonNames[$selectedFallout->Subject]:$selectedFallout->Subject)." (".strftime('%a %d.%m.%Y',strtotime($selectedFallout->Date))." von ".$selectedFallout->StartTime." bis ".$selectedFallout->EndTime.")"."</div></div>";
        }

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
            return "<div class='panel panel-danger'><div class='panel-heading' >Fehler bei der Datumsauswahl!</div></div>";
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

                $output .= "<div class='panel panel-default possible' id='".getUniqueId($optimal)."' data-sort='".$optimal->Priority."'><div class='panel-heading' data-toggle='collapse' href='#collapse".$key."'>".$movableLessons[$optimal->Subject]." (".strftime('%a %d.%m.%Y',strtotime($optimal->Date))." von ".$optimal->StartTime." bis ".$optimal->EndTime.") [".$optimal->Teacher." / ".$optimal->Location."]"."</div><div id='collapse".$key."' class='panel-collapse collapse'><div class='panel-body'><div class='table-holder'><a tabindex=\"0\" class=\"btn btn-sm btn-default\" role=\"button\" data-toggle=\"popover\" data-placement=\"top\" data-content=\"".generateTimeTable(getUniqueId($optimal),$baseDate,$selectedFallout,$optimal_candidates)."\"  data-html=\"true\" data-template=\"&lt;div class=&quot;popover&quot; style=&quot;min-height: 100% !important; min-width: 100% !important;&quot; role=&quot;tooltip&quot;&gt;&lt;div class=&quot;arrow&quot;&gt;&lt;/div&gt;&lt;div class=&quot;popover-content&quot;&gt;&lt;/div&gt;&lt;/div&gt;\">Tabelle ansehen</a></div></div></div></div>";
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
        $output .= "<script type='text/javascript'>  ";

        $x = 0;
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