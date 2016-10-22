<?php
namespace AmosCalamida\Kzoreschedule\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Amos Calamida <amos@calamida.ch>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Change
 */
class Change extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * courseId
     *
     * @var int
     * @validate NotEmpty
     */
    protected $courseId = 0;
    

    /**
     * originalLesson
     *
     * @var \DateTime
     *
     */
    protected $originalLesson = null;
    
    /**
     * changedLesson
     *
     * @var \DateTime
     *
     */
    protected $changedLesson = null;
    
    /**
     * secretaryAnswer
     *
     * Possible Values: 0 => Not yet answered, 1 => denied, 2 => approved
     *
     * @var integer
     */
    protected $secretaryAnswer = 0;
    
    /**
     * secretaryComment
     *
     * @var string
     */
    protected $secretaryComment = '';
    
    /**
     * room
     *
     * @var string
     * @validate StringLength(maximum=10)
     */
    protected $room = '';
    
    /**
     * teacherAnswer
     *
     * Possible Values: 0 => Not yet answered, 1 => denied, 2 => approved
     *
     * @var integer
     */
    protected $teacherAnswer = 0;
    
    /**
     * teacherComment
     *
     * @var string
     */
    protected $teacherComment = '';
    
    /**
     * Returns the courseId
     *
     * @return int $courseId
     */
    public function getCourseId()
    {
        return $this->courseId;
    }
    
    /**
     * Sets the courseId
     *
     * @param int $courseId
     * @return void
     */
    public function setCourseId($courseId)
    {
        $this->courseId = $courseId;
    }
    
    /**
     * Returns the originalLesson
     *
     * @return \DateTime $originalLesson
     */
    public function getOriginalLesson()
    {
        return $this->originalLesson;
    }
    
    /**
     * Sets the originalLesson
     *
     * @param \DateTime $originalLesson
     * @return void
     */
    public function setOriginalLesson(\DateTime $originalLesson)
    {
        $this->originalLesson = $originalLesson;
    }
    
    /**
     * Returns the changedLesson
     *
     * @return \DateTime $changedLesson
     */
    public function getChangedLesson()
    {
        return $this->changedLesson;
    }
    
    /**
     * Sets the changedLesson
     *
     * @param \DateTime $changedLesson
     * @return void
     */
    public function setChangedLesson(\DateTime $changedLesson)
    {
        $this->changedLesson = $changedLesson;
    }
    
    /**
     * Returns the secretaryAnswer
     *
     * @return bool $secretaryAnswer
     */
    public function getSecretaryAnswer()
    {
        return $this->secretaryAnswer;
    }
    
    /**
     * Sets the secretaryAnswer
     *
     * @param bool $secretaryAnswer
     * @return void
     */
    public function setSecretaryAnswer($secretaryAnswer)
    {
        $this->secretaryAnswer = $secretaryAnswer;
    }
    
    /**
     * Returns the boolean state of secretaryAnswer
     *
     * @return bool
     */
    public function isSecretaryAnswer()
    {
        return $this->secretaryAnswer;
    }
    
    /**
     * Returns the secretaryComment
     *
     * @return string $secretaryComment
     */
    public function getSecretaryComment()
    {
        return $this->secretaryComment;
    }
    
    /**
     * Sets the secretaryComment
     *
     * @param string $secretaryComment
     * @return void
     */
    public function setSecretaryComment($secretaryComment)
    {
        $this->secretaryComment = $secretaryComment;
    }
    
    /**
     * Returns the room
     *
     * @return string $room
     */
    public function getRoom()
    {
        return $this->room;
    }
    
    /**
     * Sets the room
     *
     * @param string $room
     * @return void
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }
    
    /**
     * Returns the teacherAnswer
     *
     * @return bool $teacherAnswer
     */
    public function getTeacherAnswer()
    {
        return $this->teacherAnswer;
    }
    
    /**
     * Sets the teacherAnswer
     *
     * @param bool $teacherAnswer
     * @return void
     */
    public function setTeacherAnswer($teacherAnswer)
    {
        $this->teacherAnswer = $teacherAnswer;
    }
    
    /**
     * Returns the boolean state of teacherAnswer
     *
     * @return bool
     */
    public function isTeacherAnswer()
    {
        return $this->teacherAnswer;
    }
    
    /**
     * Returns the teacherComment
     *
     * @return string $teacherComment
     */
    public function getTeacherComment()
    {
        return $this->teacherComment;
    }
    
    /**
     * Sets the teacherComment
     *
     * @param string $teacherComment
     * @return void
     */
    public function setTeacherComment($teacherComment)
    {
        $this->teacherComment = $teacherComment;
    }


}