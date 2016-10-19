<?php
namespace AmosCalamida\Kzoreschedule\Validation\Validator;

class ChangedLessonValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($changedLesson)
    {
        //Check if changedLesson TimeStamp is older than Timestamp of Tomorrow
        if (date('U', strtotime($changedLesson)) <= date('U', strtotime('tomorrow'))) {
            $this->addError('changedLesson Date is older than tomorrow.', 1262341491);
            return FALSE;
        }
        //Check if changedLesson Time is one of the allowed
        if (!in_array(date("H:i", strtotime($changedLesson)), array('07:30', '08:25', '09:20', '10:25', '11:20', '12:25', '13:20', '14:05', '15:10', '16:05', '16:55'))) {
            $this->addError('changedLesson Time is not a valid lesson beginning.', 1262341492);
            return FALSE;
        }
        //Check if changedLesson Weekday is >= 6 (Saturday or Sunday)
        if (date('N', strtotime($changedLesson)) >= 6){
            $this->addError('changedLesson Date is a weekend.', 1262341493);
            return FALSE;
        }

        return TRUE;

    }

}
