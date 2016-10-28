<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;

include("Headers.php");
class CourseDetailViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * this ViewHelper searches the timetable for a course by id and returns its details
     *
     * @param integer $id - The id of the course
     * @param string $mode - the mode (either student, teacher or secretary)
     * @return array - the detailed information about the course
     */

    public function render($id, $mode)
    {


        $conditions = array('WHERE' => array("ID" => "$id"), 'ORDER' => 'Subject');
        $mod = base64_encode(json_encode($conditions));

        $user = 'rest-timetable';
        $pwd = 'a46vQJzsY9YwVhKCxjUD';
        $controller = 'data/source/timetable';
        $school = 'kzo';

        $headers = generateHeaders($user, $pwd, 'gr001');

        $request = "https://api.tam.ch/$school/$controller?mod=$mod";

        $result = exec("curl " . $request


            . ' -H "Accept:application/xml"'


            . ' -H "X-gr-AuthDate:' . $headers['X-gr-AuthDate'] . '"'


            . ' -H "Authorization:' . $headers['Authorization'] . '"'

        );

        $json = json_decode($result)->body;

        $course = $json[0];
        $subjects = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers", "");

        switch ($mode) {
            case "secretary":
                $course->Label = $subjects[$course->Subject] . " (" . $course->Teacher . ") [" . $course->Class . "]";
                break;

            case "teacher":
                $course->Label = $subjects[$course->Subject] . " (" . $course->Class . ")";
                break;

            case "student":
                $course->Label = $subjects[$course->Subject]." (".$course->Teacher.")";
                break;
        }


        return $course->Label;


    }

}

?>