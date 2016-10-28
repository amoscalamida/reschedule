<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;

include("Headers.php");
class FindCoursesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * this ViewHelper searches the timetable for courses of a specified class
     *
     * @param string $class - The class to get the courses for
     * @return array - the list of courses as object
     */

    public function render($class)
    {


        $conditions = array('WHERE' => array("Class" => "$class"), 'ORDER' => 'Subject');

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

        $class_courses = array();
        $subjects = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers", "");
        function checkArrayForObject($array, $id)
        {
            return array_filter($array, function ($object) use ($id) {
                return $object->ID == $id;
            });
        }

        foreach ($json as $course) {
            if (empty(checkArrayForObject($class_courses, $course->ID)) AND array_key_exists($course->Subject, $subjects)) {
                $course->Label = $subjects[$course->Subject]." (".$course->Teacher.")";
                array_push($class_courses, $course);
            }
        }



        return $class_courses;


    }

}

?>