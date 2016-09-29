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
 * Test case for class \AmosCalamida\Kzoreschedule\Domain\Model\Change.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Amos Calamida <amos@calamida.ch>
 */
class ChangeTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \AmosCalamida\Kzoreschedule\Domain\Model\Change
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getCourseIdReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setCourseIdForIntSetsCourseId()
	{	}

	/**
	 * @test
	 */
	public function getOriginalLessonReturnsInitialValueForDateTime()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getOriginalLesson()
		);
	}

	/**
	 * @test
	 */
	public function setOriginalLessonForDateTimeSetsOriginalLesson()
	{
		$dateTimeFixture = new \DateTime();
		$this->subject->setOriginalLesson($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'originalLesson',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getChangedLessonReturnsInitialValueForDateTime()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getChangedLesson()
		);
	}

	/**
	 * @test
	 */
	public function setChangedLessonForDateTimeSetsChangedLesson()
	{
		$dateTimeFixture = new \DateTime();
		$this->subject->setChangedLesson($dateTimeFixture);

		$this->assertAttributeEquals(
			$dateTimeFixture,
			'changedLesson',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSecretaryAnswerReturnsInitialValueForBool()
	{
		$this->assertSame(
			FALSE,
			$this->subject->getSecretaryAnswer()
		);
	}

	/**
	 * @test
	 */
	public function setSecretaryAnswerForBoolSetsSecretaryAnswer()
	{
		$this->subject->setSecretaryAnswer(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'secretaryAnswer',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getSecretaryCommentReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getSecretaryComment()
		);
	}

	/**
	 * @test
	 */
	public function setSecretaryCommentForStringSetsSecretaryComment()
	{
		$this->subject->setSecretaryComment('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'secretaryComment',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getRoomReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getRoom()
		);
	}

	/**
	 * @test
	 */
	public function setRoomForStringSetsRoom()
	{
		$this->subject->setRoom('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'room',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTeacherAnswerReturnsInitialValueForBool()
	{
		$this->assertSame(
			FALSE,
			$this->subject->getTeacherAnswer()
		);
	}

	/**
	 * @test
	 */
	public function setTeacherAnswerForBoolSetsTeacherAnswer()
	{
		$this->subject->setTeacherAnswer(TRUE);

		$this->assertAttributeEquals(
			TRUE,
			'teacherAnswer',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTeacherCommentReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getTeacherComment()
		);
	}

	/**
	 * @test
	 */
	public function setTeacherCommentForStringSetsTeacherComment()
	{
		$this->subject->setTeacherComment('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'teacherComment',
			$this->subject
		);
	}
}
