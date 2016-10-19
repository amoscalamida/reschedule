<?php
namespace \AmosCalamida\Kzoreschedule\Domain\Validator;

class OriginalLessonValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    public function isValid($originalLesson)
    {

        if (date('U',strtotime($originalLesson)) <= date('U',strtotime('tomorrow'))){
            $this->addError('changedLesson Date is older than tomorrow.', 1262341491);
            return FALSE;
        }

        if (!in_array(date("H:i",strtotime($originalLesson)), array('07:30','08:25','09:20','10:25','11:20','12:25','13:20','14:05','15:10','16:05','16:55'))) {
            $this->addError('changedLesson Time is not a valid lesson beginning.', 1262341492);
            return FALSE;
        }

        return TRUE;
    }
}
