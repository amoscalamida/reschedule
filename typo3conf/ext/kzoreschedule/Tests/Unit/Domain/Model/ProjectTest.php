<?php

namespace AmosCalamida\Kzoreschedule\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Amos Calamida <amos@calamida.ch>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \AmosCalamida\Kzoreschedule\Domain\Model\Project.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Amos Calamida <amos@calamida.ch>
 */
class ProjectTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \AmosCalamida\Kzoreschedule\Domain\Model\Project
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \AmosCalamida\Kzoreschedule\Domain\Model\Project();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getUserIdReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setUserIdForIntSetsUserId()
	{	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle()
	{
		$this->subject->setTitle('Title');

		$this->assertAttributeEquals(
			'Title',
			'title',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCommentReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getComment()
		);
	}

	/**
	 * @test
	 */
	public function setCommentForStringSetsComment()
	{
		$this->subject->setComment('Title');

		$this->assertAttributeEquals(
			'Title',
			'comment',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getProgressReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setProgressForIntSetsProgress()
	{	}

	/**
	 * @test
	 */
	public function getChangesReturnsInitialValueForChange()
	{
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->subject->getChanges()
		);
	}

	/**
	 * @test
	 */
	public function setChangesForObjectStorageContainingChangeSetsChanges()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();
		$objectStorageHoldingExactlyOneChanges = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneChanges->attach($change);
		$this->subject->setChanges($objectStorageHoldingExactlyOneChanges);

		$this->assertAttributeEquals(
			$objectStorageHoldingExactlyOneChanges,
			'changes',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function addChangeToObjectStorageHoldingChanges()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();
		$changesObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('attach'), array(), '', FALSE);
		$changesObjectStorageMock->expects($this->once())->method('attach')->with($this->equalTo($change));
		$this->inject($this->subject, 'changes', $changesObjectStorageMock);

		$this->subject->addChange($change);
	}

	/**
	 * @test
	 */
	public function removeChangeFromObjectStorageHoldingChanges()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();
		$changesObjectStorageMock = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array('detach'), array(), '', FALSE);
		$changesObjectStorageMock->expects($this->once())->method('detach')->with($this->equalTo($change));
		$this->inject($this->subject, 'changes', $changesObjectStorageMock);

		$this->subject->removeChange($change);

	}
}
