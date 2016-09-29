<?php
namespace AmosCalamida\Kzoreschedule\Tests\Unit\Controller;
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
 * Test case for class AmosCalamida\Kzoreschedule\Controller\ChangeController.
 *
 * @author Amos Calamida <amos@calamida.ch>
 */
class ChangeControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \AmosCalamida\Kzoreschedule\Controller\ChangeController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('AmosCalamida\\Kzoreschedule\\Controller\\ChangeController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllChangesFromRepositoryAndAssignsThemToView()
	{

		$allChanges = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$changeRepository = $this->getMock('AmosCalamida\\Kzoreschedule\\Domain\\Repository\\ChangeRepository', array('findAll'), array(), '', FALSE);
		$changeRepository->expects($this->once())->method('findAll')->will($this->returnValue($allChanges));
		$this->inject($this->subject, 'changeRepository', $changeRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('changes', $allChanges);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenChangeToView()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('change', $change);

		$this->subject->showAction($change);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenChangeToChangeRepository()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();

		$changeRepository = $this->getMock('AmosCalamida\\Kzoreschedule\\Domain\\Repository\\ChangeRepository', array('add'), array(), '', FALSE);
		$changeRepository->expects($this->once())->method('add')->with($change);
		$this->inject($this->subject, 'changeRepository', $changeRepository);

		$this->subject->createAction($change);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenChangeToView()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('change', $change);

		$this->subject->editAction($change);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenChangeInChangeRepository()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();

		$changeRepository = $this->getMock('AmosCalamida\\Kzoreschedule\\Domain\\Repository\\ChangeRepository', array('update'), array(), '', FALSE);
		$changeRepository->expects($this->once())->method('update')->with($change);
		$this->inject($this->subject, 'changeRepository', $changeRepository);

		$this->subject->updateAction($change);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenChangeFromChangeRepository()
	{
		$change = new \AmosCalamida\Kzoreschedule\Domain\Model\Change();

		$changeRepository = $this->getMock('AmosCalamida\\Kzoreschedule\\Domain\\Repository\\ChangeRepository', array('remove'), array(), '', FALSE);
		$changeRepository->expects($this->once())->method('remove')->with($change);
		$this->inject($this->subject, 'changeRepository', $changeRepository);

		$this->subject->deleteAction($change);
	}
}
