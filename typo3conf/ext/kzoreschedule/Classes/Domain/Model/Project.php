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
 * Project
 */
class Project extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{


	/** 
	*crdate
	*
	* @var int 
	*/
	protected $crdate;

    /**
     * userId
     *
     * @var int
     * @validate NotEmpty
     */
    protected $userId = 0;
    
    /**
     * title
     *
     * @var string
     * @validate NotEmpty
     */
    protected $title = '';
    
    /**
     * comment
     *
     * @var string
     * @validate NotEmpty
     * @validate StringLength(minimum=4, maximum=50)
     */
    protected $comment = '';
    
    /**
     * progress
     *
     * @var int
     */
    protected $progress = 0;
    
    /**
     * changes
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\AmosCalamida\Kzoreschedule\Domain\Model\Change>
     * @cascade remove
     */
    protected $changes = null;
    
    /**
     * __construct
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }
    
    /**
     * Initializes all ObjectStorage properties
     * You may modify the constructor of this class
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->changes = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }
    
    /**
	* Returns the crdate
	*
	* @return int $crdate
	*/
	public function getCrdate() 
	{
    	return $this->crdate;
	}
	
    /**
     * Returns the userId
     *
     * @return int $userId
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * Sets the userId
     *
     * @param int $userId
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Returns the comment
     *
     * @return string $comment
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    /**
     * Sets the comment
     *
     * @param string $comment
     * @return void
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }
    
    /**
     * Returns the progress
     *
     * @return int $progress
     */
    public function getProgress()
    {
        return $this->progress;
    }
    
    /**
     * Sets the progress
     *
     * @param int $progress
     * @return void
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;
    }
    
    /**
     * Adds a Change
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     */
    public function addChange(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change)
    {
        $this->changes->attach($change);
    }
    
    /**
     * Removes a Change
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $changeToRemove The Change to be removed
     * @return void
     */
    public function removeChange(\AmosCalamida\Kzoreschedule\Domain\Model\Change $changeToRemove)
    {
        $this->changes->detach($changeToRemove);
    }
    
    /**
     * Returns the changes
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\AmosCalamida\Kzoreschedule\Domain\Model\Change> $changes
     */
    public function getChanges()
    {
        return $this->changes;
    }
    
    /**
     * Sets the changes
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\AmosCalamida\Kzoreschedule\Domain\Model\Change> $changes
     * @return void
     */
    public function setChanges(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $changes)
    {
        $this->changes = $changes;
    }

}