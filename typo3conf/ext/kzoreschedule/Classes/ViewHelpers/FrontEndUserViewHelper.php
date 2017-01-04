<?php

namespace AmosCalamida\Kzoreschedule\ViewHelpers;


class FrontEndUserViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * this ViewHelper searches infromation for the current FE User
     *
     * @param string $attribute - The attribute to get for the FE User
     * @return mixed - the value of the attribut
     */

    public function render($attribute)
    {

        $value = $GLOBALS['TSFE']->fe_user->user[$attribute];

        if (!empty($value)) {
            return $value;
        }
        else {
            return "Error. Attribute or Value not found!";
        }


    }
}

?>