<?php
namespace AmosCalamida\Kzoreschedule\Validation\Validator;

class OriginalLessonValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($originalLesson)
    {
        //Check if originalLesson TimeStamp is older than Timestamp of Tomorrow
        if (date('U', strtotime($originalLesson)) <= date('U', strtotime('tomorrow'))) {
            $this->addError('originalLesson Date is older than tomorrow.', 1262341481);
            return FALSE;
        }
        //Check if originalLesson Time is one of the allowed
        if (!in_array(date("H:i", strtotime($originalLesson)), array('07:30', '08:25', '09:20', '10:25', '11:20', '12:25', '13:20', '14:05', '15:10', '16:05', '16:55'))) {
            $this->addError('originalLesson Time is not a valid lesson beginning.', 1262341482);
            return FALSE;
        }
        //Check if originalLesson Weekday is >= 6 (Saturday or Sunday)
        if (date('N', strtotime($originalLesson)) >= 6){
            $this->addError('originalLesson Date is a weekend.', 1262341483);
            return FALSE;
        }

        return TRUE;


    }


}