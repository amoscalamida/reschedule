<?php
namespace AmosCalamida\Kzoreschedule\Controller;


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
use TYPO3\CMS\Core\Cache\Backend\NullBackend;

setlocale (LC_ALL, 'de_CH.UTF-8');

/**
 * ChangeController
 */
class ChangeController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * changeRepository
     *
     * @var \AmosCalamida\Kzoreschedule\Domain\Repository\ChangeRepository
     * @inject
     */
    protected $changeRepository = NULL;

    /**
     * projectRepository
     *
     * @var \AmosCalamida\Kzoreschedule\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = NULL;

    
    
    /**
     * action show
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     *
     */
    public function showAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, \AmosCalamida\Kzoreschedule\Domain\Model\Change $change)
    {
        $this->view->assign('change', $change);
        $this->view->assign('project', $project);
    }

    /**
     * action secretaryShow
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     *
     */
    public function secretaryShowAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, \AmosCalamida\Kzoreschedule\Domain\Model\Change $change)
    {
        $this->view->assign('change', $change);
        $this->view->assign('project', $project);
    }

    /**
     * action new
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $newChange
     * @return string
     * @dontvalidate $newChange
     */
    public function newAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, \AmosCalamida\Kzoreschedule\Domain\Model\Change $newChange = Null)
    {
//        $includes = $this->includeJavaScript("https://code.jquery.com/","jquery-2.2.4.min.js",true);
//        $includes .= $this->includeJavaScript("Resources/Public/js/","jquery.datetimepicker.min.js");
//        $includes .= $this->includeStylesheet("Resources/Public/css/","jquery.datetimepicker.css");
//        $GLOBALS['TSFE']->additionalHeaderData['kzoreschedule'] = $includes;
        $this->view->assign('project',$project);
        $this->view->assign('newChange', $newChange);
    }


    /**
     * initialize create action
     *
     * @param void
     */
    public function initializeCreateAction() {
        $this->arguments->getArgument('newChange')
            ->getPropertyMappingConfiguration()->forProperty('originalLesson')
            ->setTypeConverterOption(
                'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                'd.m.Y H:i'
            );
        $this->arguments->getArgument('newChange')
            ->getPropertyMappingConfiguration()->forProperty('changedLesson')
            ->setTypeConverterOption(
                'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                'd.m.Y H:i'
            );
    }

    /**
     * action create
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $newChange
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @return string
     */
    public function createAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, \AmosCalamida\Kzoreschedule\Domain\Model\Change $newChange)
    {
        $project->addChange($newChange);
        $this->projectRepository->update($project);
        $this->redirect("show", "Project",NULL,array("project" => $project));
    }
    
    /**
     * action edit
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @ignorevalidation $change
     * @return void
     */
    public function editAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change, \AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->view->assign('change', $change);
        $this->view->assign('project', $project);
    }

    /**
     * initialize update action
     *
     * @param void
     */
    public function initializeUpdateAction() {
        $this->arguments->getArgument('change')
            ->getPropertyMappingConfiguration()->forProperty('originalLesson')
            ->setTypeConverterOption(
                'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                'd.m.Y H:i'
            );
        $this->arguments->getArgument('change')
            ->getPropertyMappingConfiguration()->forProperty('changedLesson')
            ->setTypeConverterOption(
                'TYPO3\\CMS\\Extbase\\Property\\TypeConverter\\DateTimeConverter',
                \TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                'd.m.Y H:i'
            );
    }

    /**
     * action update
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     */
    public function updateAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change,\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->addFlashMessage('Die Verschiebung wurde aktualisiert.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->changeRepository->update($change);
        $this->redirect("show", "Project",NULL,array("project" => $project));
    }
    
    /**
     * action delete
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     */
    public function deleteAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change,\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->addFlashMessage('Die Verschiebung wurde gelöscht.', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->changeRepository->remove($change);
        $this->redirect("show", "Project",NULL,array("project" => $project));
    }
    
    /**
     * action secretaryProcess
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @param integer $answer Prefills the Answer (either approved [2] or denied [1])
     * @return string
     */
    public function secretaryProcessAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change, $answer)
    {
        $change->setSecretaryAnswer((($answer == 1)?2:1));
        if ($answer == 0) {
            $change->setRoom("");
        }
        $this->addFlashMessage('Die Verschiebung wird '.(($change->getSecretaryAnswer() == 2)?'akzeptiert':'abgelehnt').'', '', ($change->getSecretaryAnswer() == 2)?\TYPO3\CMS\Core\Messaging\AbstractMessage::OK : \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->view->assign('change', $change);
        $this->view->assign('answer', $answer);


    }

    /**
     * action secretaryProcessCompletion
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     */
    public function secretaryProcessCompletionAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change)
    {
        $this->addFlashMessage('Die Verschiebung wurde erfolgreich '.(($change->getSecretaryAnswer() == 2)?'akzeptiert':'abgelehnt').'. ', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->changeRepository->update($change);
        $this->redirect("secretaryList","Project");

    }


    /**
     * action teacherAnswer
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @param integer $answer Prefills the Answer (either approved [2] or denied [1])
     * @return string
     */
    public function teacherAnswerAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change, $answer)
    {
        $change->setTeacherAnswer((($answer == 1)?2:1));
        $this->addFlashMessage('Sie sind mit der Verschiebung '.(($change->getTeacherAnswer() == 2)?'einverstanden':'nicht einverstanden').'', '', ($change->getTeacherAnswer() == 2)?\TYPO3\CMS\Core\Messaging\AbstractMessage::OK : \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->view->assign('change', $change);

    }

    /**
     * action teacherAnswerCompletion
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return void
     */
    public function teacherAnswerCompletionAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change)
    {
        $this->addFlashMessage('Die Verschiebung wurde '.(($change->getTeacherAnswer() == 2)?'akzeptiert':'abgelehnt').' und ist nun unter "Beantwortete Verschiebungen" zu finden. ', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->changeRepository->update($change);
        $this->redirect("teacherList","Project");

    }

    /**
     * action findRoom
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Change $change
     * @return string
     */
    public function findRoomAction(\AmosCalamida\Kzoreschedule\Domain\Model\Change $change) {

    $this->view->assign("Change", $change);
    if($this->request->hasArgument('category')){
            $category = $this->request->getArgument('category');
            $this->view->assign("category", $category);
    }


    }

}