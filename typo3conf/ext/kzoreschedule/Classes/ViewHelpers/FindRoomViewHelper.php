<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;

include_once("tam_connection.php");

class FindRoomViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * this ViewHelper searches the timetable for available rooms in a specified lesson
     *
     * @param datetime $datetime - The date and time of the lesson
     * @param string $category - The room category requested
     * @return string - the list of available rooms as html output
     */

    public function render($datetime,$category) {

        $conditions = array('WHERE' => array(), 'ORDER' => 'Date');
        $json = makeRequest($conditions);


        $rooms = array();
        //split the input datetime to date and startTime of lesson
        $Query = array("Date" => $datetime->format('Y-m-d'), "StartTime" => $datetime->format('H:i:s'));
        $result = array();
        $course_count = 0;

        foreach ($json as $course) {
            //push all found rooms in reference-array $rooms only if they don't already exist in it
            if (!in_array($course->Location,$rooms)) {
                array_push($rooms, $course->Location);
            }
        }

        foreach ($json as $course) {
            //compare date and time for each course with date and time from the passed change
            if ($course->Date == $Query["Date"] AND $course->StartTime == $Query["StartTime"])
            {
                /** course takes place at the same time as the planned change
                 *
                 * search the location (room) of the course in reference-array
                 * if room is found it will be removed from the reference-array
                 * because the room is not available.
                 */
                if(($key = array_search($course->Location, $rooms)) !== false) {
                    unset($rooms[$key]);
                    $course_count++;
                }
            }
        }


        // DEBUG OUTPUT (Uncomment below)
        //$rooms = array("16","01","02","25","50","1G","1E","32", "C2", "Z5","Z4","THA","THJ","P3");
        //$course_count = count($rooms);


        /** if no course matches with the change time there are no lessons this day
         * -> timetable data is not available for this date
         * This prevents the ViewHelper from returning anything
         */
        if ($course_count == 0) {
            return "Suche fehlgeschlagen: Für dieses Datum stehen (noch) keine Stundenpläne zur Verfügung. </br> ";
            die();
        }



        foreach ($rooms as $room) {

            switch ($category) {
                // general rooms (every room with no prefix)
                case "G":
                    if(substr($room, 0, 1) != 'C' && substr($room, 0, 1) != 'B' && substr($room, 0, 2) != 'TH' && substr($room, 0, 1) != 'Z' && substr($room, 0, 1) != 'P' ){
                        array_push($result,$room);
                    }
                    break;
                // chemistry rooms
                case "C":
                    if(substr($room, 0, 1) == 'C'){
                        array_push($result,$room);
                    }
                    break;
                // biology rooms
                case "B":
                    if(substr($room, 0, 1) == 'B'){
                        array_push($result,$room);
                    }
                    break;
                // sports rooms
                case "T":
                    if(substr($room, 0, 2) == 'TH') {
                        array_push($result,$room);
                    }
                    break;
                // physics rooms
                case "P":
                    if(substr($room, 0, 1) == 'P'){
                        array_push($result,$room);
                    }
                    break;
                // arts rooms
                case "Z":
                    if(substr($room, 0, 1) == 'Z'){
                        array_push($result,$room);
                    }
                    break;
                default:
                    return "Fehler bei der Kategoriewahl. Bitte erneut versuchen!";
                    die();
                    break;
            }
        }
        // sort the result array to display rooms in ascending order
        sort($result);
        // count the total rooms in the result array for proper display in 3 columns
        $arr_length = count($result);
        $row_length = ceil($arr_length/3);
        // if some rooms match the criteria generate the list
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
            // return and display the list
            return $list;
        }
        else {
            // return and display error if no matching rooms found
            return "Keine freien Zimmer gefunden. </br> Bitte Einschränkung entfernen und erneut versuchen.";
        }


        }

    }

?>