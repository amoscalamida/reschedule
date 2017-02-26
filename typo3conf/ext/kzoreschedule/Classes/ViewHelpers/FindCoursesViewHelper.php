<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;

include_once("tam_connection.php");

class FindCoursesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * this ViewHelper searches the timetable for courses of a specified class
     *
     * @param string $class - The class to get the courses for
     * @param boolean $onlyMovable - if only movable courses or all should be returned
     * @return array - the list of courses as object
     */

    public function render($class,$onlyMovable)
    {


        $conditions = array('WHERE' => array("Class" => "$class"), 'ORDER' => 'Subject');

        $json = makeRequest($conditions);

        $class_courses = array();
        if(!$onlyMovable) {
            $subjects = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten","BG1" => "Bildnerisches Gestalten 1","BG2" => "Bildnerisches Gestalten 2","MU1" => "Musik 1","MU2" => "Musik 2", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers","+SP" => "FF Spanisch", "+IT" => "FF Italienisch","TM" => "Sport Herren","TF" => "Sport Damen", "K" => "Klassenstunde","FB" => "Französisch Besprechung", "DB" => "Deutsch Besprechung");
        } else {
            $subjects = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers", "DB" => "Deutsch Besprechung", "K" => "Klassenstunde", "FB" => "Französisch Besprechung");

        }
        function checkArrayForObject($array, $id)
        {
            return array_filter($array, function ($object) use ($id) {
                return $object->ID == $id;
            });
        }

        foreach ($json as $course) {
            if (empty(checkArrayForObject($class_courses, $course->ID)) AND array_key_exists($course->Subject, $subjects)) {
                if(!$onlyMovable) {
                    $course->Label = $subjects[$course->Subject];
                } else {
                    $course->Label = $subjects[$course->Subject]." (".$course->Teacher.")";
                }
                array_push($class_courses, $course);
            }
        }



        return $class_courses;


    }

}

?>