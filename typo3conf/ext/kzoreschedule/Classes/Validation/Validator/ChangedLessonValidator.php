<?php
namespace AmosCalamida\Kzoreschedule\Validation\Validator;

class ChangedLessonValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($changedLesson)
    {

        $changedLesson = $changedLesson->format("d.m.Y H:i");

        //Check if changedLesson TimeStamp is older than Timestamp of Tomorrow
        if (date('U', strtotime($changedLesson)) <= date('U', strtotime('tomorrow'))) {
            $this->addError('changedLesson Date is older than tomorrow.', 1262341481);
            return FALSE;
        }
        //Check if changedLesson Time is one of the allowed
        if (!in_array(date("H:i", strtotime($changedLesson)), array('07:30', '08:25', '09:20', '10:25', '11:20', '12:25', '13:20', '14:15', '15:10', '16:05', '16:55'))) {
            $this->addError('changedLesson Time is not a valid lesson beginning.', 1262341482);
            return FALSE;
        }
        //Check if changedLesson Weekday is >= 6 (Saturday or Sunday)
        if (date('N', strtotime($changedLesson)) >= 6){
            $this->addError('changedLesson Date is a weekend.', 1262341483);
            return FALSE;
        }

        return FALSE;

    }

}
