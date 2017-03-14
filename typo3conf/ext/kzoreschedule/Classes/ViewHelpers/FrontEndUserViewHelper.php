<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;
include_once("kzo_connection.php");

class FrontEndUserViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * this ViewHelper searches infromation for the current FE User on the Data API
     *
     * @param string $attribute - The attribute to get for the FE User
     * @return mixed - the value of the attribut
     */

    public function render($attribute)
    {

        $value = $GLOBALS['TSFE']->fe_user->user['username'];

        $result = makeKZORequest($value,"loginname","student");

        if ($result->$attribute != "") {
            return $result->$attribute;
        } else {
            return "Attribute not found";
        }
    }
}

?>