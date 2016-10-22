<?php

namespace AmosCalamida\Kzoreschedule\Domain\Validator;

class ChangeValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{

    /**
     * validates the change object
     *
     * @param object $change - The objet to validate
     * @return boolean - validation result
     */

    public function isValid($change)
    {

        if (!$change instanceof \AmosCalamida\Kzoreschedule\Domain\Model\Change) {
            $this->addError('The given Object is not a Change.', 1262341470);
            return FALSE;
        }

        $originalLesson = $change->getOriginalLesson();
        $changedLesson = $change->getChangedLesson();

        //Check if TimeStamp is older than Timestamp of Tomorrow
        if (date('U', strtotime($originalLesson)) <= date('U', strtotime('tomorrow'))) {
            $this->addError('originalLesson Date is older than tomorrow.', 1262341481);
            return FALSE;
        }

        //Check if Time is one of the allowed
        if (!in_array(date("H:i", strtotime($originalLesson)), array('07:30', '08:25', '09:20', '10:25', '11:20', '12:25', '13:20', '14:05', '15:10', '16:05', '16:55'))) {
            $this->addError('originalLesson Time is not a valid lesson beginning.', 1262341482);
            return FALSE;
        }
        //Check if Weekday is >= 6 (Saturday or Sunday)
        if (date('N', strtotime($originalLesson)) >= 6) {
            $this->addError('originalLesson Date is a weekend.', 1262341483);
            return FALSE;
        }

        //Check if TimeStamp is older than Timestamp of Tomorrow
        if (date('U', strtotime($changedLesson)) <= date('U', strtotime('tomorrow'))) {
            $this->addError('changedLesson Date is older than tomorrow.', 1262341491);
            return FALSE;
        }
        //Check if  Time is one of the allowed
        if (!in_array(date("H:i", strtotime($changedLesson)), array('07:30', '08:25', '09:20', '10:25', '11:20', '12:25', '13:20', '14:05', '15:10', '16:05', '16:55'))) {
            $this->addError('changedLesson Time is not a valid lesson beginning.', 1262341492);
            return FALSE;
        }
        //Check if Weekday is >= 6 (Saturday or Sunday)
        if (date('N', strtotime($changedLesson)) >= 6){
            $this->addError('changedLesson Date is a weekend.', 1262341493);
            return FALSE;
        }

        return TRUE;
    }
}