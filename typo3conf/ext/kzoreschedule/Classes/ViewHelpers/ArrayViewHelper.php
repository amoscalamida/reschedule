<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;

class ArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * gets the element and property of the array provided
     *
     * @param string $object The array to search
     * @param integer $key The array key
     * @param string $property The property to get
     * @return string the property's value
     */
    
    public function render($object,$key,$property) {
            if (array_key_exists($key, $object)) {
                if ($property != 'none') {
                    $propFunc = "get" . ucfirst($property);
                    return $object[$key]->$propFunc();
                } else {
                    return $object[$key];
                }
            } else {
                return NULL;
            }
    }
}

?>