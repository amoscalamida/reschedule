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

setlocale (LC_ALL, 'de_CH.UTF-8');


/**
 * ProjectController
 */
class ProjectController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     *
     * injection of persistence manager to be able to persist manually
     *
     * @inject
     */
    protected $persistenceManager;


    /**
     * projectRepository
     *
     * injecting the project repository to create and manipulate projects
     *
     * @var \AmosCalamida\Kzoreschedule\Domain\Repository\ProjectRepository
     * @inject
     */
    protected $projectRepository = NULL;


    /**
     * action list
     *
     * list all projects for logged in FE User
     *
     * @return void
     */
    public function listAction()
    {
       $projects = $this->projectRepository->findByUserId($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('projects', $projects);
    }
    
    /**
     * action show
     *
     * Detail View of Project for Student
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @return void
     */
    public function showAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $disagree = 0;
        $secretaryTouches = 0;
        foreach($project->getChanges() as $change) {

            if ($change->getTeacherAnswer() == 0 OR $change->getTeacherAnswer() == 1) {
                $disagree++;
            }
            if ($change->getSecretaryAnswer() != 0) {
                $secretaryTouches++;
            }

        }
        ($disagree==0)?$teachersAgree = true : $teachersAgree = false;
        ($secretaryTouches==0)?$secretaryDidNotTouch = true : $secretaryDidNotTouch = false;
        

        $this->view->assign('allTeachersAgree', $teachersAgree);
        $this->view->assign('secretaryDidNotTouch',$secretaryDidNotTouch);
        $this->view->assign('project', $project);
    }

    /**
     * action secretaryShow
     *
     * Detail View of Project for Secretary
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @return void
     */
    public function secretaryShowAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->view->assign('project', $project);
    }
    
    /**
     * action new
     *
     * renders the project creation form
     *
     *
     * @return void
     */
    public function newAction()
    {
        
    }
    
    /**
     * action create
     *
     * get the new project object from the form, add the uid of the current FE User as userId
     * and persist it. Redirect to the detail view of the created project
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $newProject
     * @return void
     */
    public function createAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $newProject)
    {
     	$newProject->setUserId($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->addFlashMessage('Verschiebungen können nun in der Tabelle unten eingetragen werden', 'Das Projekt wurde erstellt.', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->projectRepository->add($newProject);
        $this->persistenceManager->persistAll();
        $this->redirect('show',NULL,NULL,array('project' => $newProject));
    }
    
    /**
     * action edit
     *
     * render the edit form and assign the project object to view
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @ignorevalidation $project
     * @return void
     */
    public function editAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->view->assign('project', $project);
    }
    
    /**
     * action update
     *
     * update the repository object and redirect to the project detail view
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @return void
     */
    public function updateAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->addFlashMessage('Die Projektinformationen wurden aktualisiert', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->projectRepository->update($project);
        $this->redirect('show',NULL,NULL,array('project' => $project));
    }
    
    /**
     * action delete
     *
     * remove the project and all of its changes from repository
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @return void
     */
    public function deleteAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $this->addFlashMessage('Das Projekt wurde gelöscht', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->projectRepository->remove($project);
        $this->redirect('studentList');
    }
    
    /**
     * action secretaryChange
     *
     * secretary action to either close or reopen a project
     * on close first check for unanswered changes
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param string $modification modification to perform (either open or close)
     * @return void
     */
    public function secretaryChangeAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, $modification)
    {
        if ($modification == "close") { //Close Project
            // Check if there are still unhandled changes
            $changes = $project->getChanges();
            $errorCount = 0;
            foreach ($changes as $change) {
                if ($change->getSecretaryAnswer() == 0) {
                    $errorCount++;
                }
            }
            if ($errorCount > 0) {
                $this->addFlashMessage('Es ' . (($errorCount == 1) ? 'wurde' : 'wurden') . ' ' . $errorCount . ' ' . (($errorCount == 1) ? 'Verschiebung' : 'Verschiebungen') . ' gefunden, welche noch nicht beantwortet ' . (($errorCount == 1) ? 'ist' : 'sind') . '.', 'Projekt konnte nicht geschlossen werden!', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            } else {
                // TODO: send E-Mail to Teacher and Student to Inform about outcome
                $project->setProgress("3");
                $this->projectRepository->update($project);
                $this->addFlashMessage("Projekt erfolgreich geschlossen");
            }
        }
        if ($modification == "open") { //reopen Project
            $project->setProgress("2");
            $this->projectRepository->update($project);
            $this->addFlashMessage("Projekt erfolgreich wieder geöffnet");
        }
        $this->redirect("secretaryList");
    }

    /**
     * action projectStatusAdjust
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param string $level
     *
     * @return void
     */
    public function projectStatusAdjustAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, $level)
    {
        switch ($level) {
            case 'student':
                $level = 0;
                break;

            case 'teacher':
                $level = 1;
                break;

            case 'secretary':
                $level = 2;
                break;
        }


        $project->setProgress($level);
        $this->projectRepository->update($project);
        $this->redirect("show",NULL,NULL,array('project' => $project));

    }



    /**
     * action deleteAll
     *
     * delete all projects (massive action / only available from admin panel)
     *
     * @return void
     */
    public function deleteAllAction()
    {

        foreach ($this->projectRepository->findAll() as $project) {
            $this->projectRepository->remove($project);
        }



        
    }
    
    /**
     * action studentList
     *
     * list all projects for student view and check the teacher and secretary answer status
     *
     * @return void
     */
    public function studentListAction()
    {

         $projects = $this->projectRepository->findByUserId($GLOBALS['TSFE']->fe_user->user['uid']);
        $this->view->assign('projects', $projects);

        $secretaryAnswers = array ();
        $teacherAnswers = array();
        foreach ($projects as $project) {

            array_push($secretaryAnswers, $this->getAnswerProgress($project,"secretary"));
            array_push($teacherAnswers, $this->getAnswerProgress($project,"teacher"));

        }

        $this->view->assign('secretaryAnswers',$secretaryAnswers);
        $this->view->assign('teacherAnswers', $teacherAnswers);
    }
    
    /**
     * action adminList
     *
     * @return void
     */
    public function adminListAction()
    {
     $projects = $this->projectRepository->findAll();
     $this->view->assign('projects', $projects);   
    }
    
    /**
     * action teacherList
     *
     * list all changes for teacher view
     *
     * @param integer $filter - filter for results (either 0: unanswered or 1: answered)
     * @return void
     */
    public function teacherListAction($filter = 0)
    {

        $projects = $this->projectRepository->findByPublicAndCourses(array("240","250","289","580","123","239","212"));
        $allowed_changes = array();
        $allowed_changes_project = array();
        foreach ($projects as $project) {
            if ($project->getProgress() >0) {
                foreach ($project->getChanges() as $change) {
                            array_push($allowed_changes, $change);
                            array_push($allowed_changes_project, $project);
                }
            }
        }
    if (!is_null($allowed_changes)) {
        foreach ($allowed_changes as $changeKey => $change) {

            if ($filter == 1) { // show only answered
                if ($change->getTeacherAnswer() == 0) { // if answered
                   unset($allowed_changes[$changeKey]);
                    unset($allowed_changes_project[$changeKey]);
                }
            } else { // show only unanswered
                if ($change->getTeacherAnswer() != 0) { // if unanswered
                    unset($allowed_changes[$changeKey]);
                    unset($allowed_changes_project[$changeKey]);
                }
            }
        }
            switch ($filter) {
                case 0:
                    $divfilter = 1;
                    break;
                case 1:
                    $divfilter = 0;
                    break;
            }
            $this->view->assign('changes', $allowed_changes);
            $this->view->assign('projects', $allowed_changes_project);
            $this->view->assign('divfilter', $divfilter);

        }
    }
    /**
     * action secretaryList
     *
     * list projects for sectretary view
     *
     * @param integer $progress - show either opened (progress: 2) or already closed projects (progress: 3)
     * @return void
     */
    public function secretaryListAction($progress = 2)
    {
        if ($progress == 2) {
            $divprogress = 3;
        } else {
            $divprogress = 2;
        }
        $projects = $this->projectRepository->findByProgress($progress);
        $this->view->assign('divprogress',$divprogress);
        $this->view->assign('projects', $projects);
    }


    /**
     * function getAnswerProgress
     *
     * function to get the status of teacher and secretary answers
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @param  string $category - the category to get the answers from (either "secretary" or "teacher")
     *
     * @return integer - (0 => wait, 1 => all disallowed, 2 => all allowed, 3 => partly allowed)
     */
    public function getAnswerProgress(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, $category) {

        $allowed = 0;
        $disallowed = 0;

        foreach($project->getChanges() as $change) {
            switch ($category) {
                case 'teacher':
                    $answer = $change->getTeacherAnswer();
                    break;
                case 'secretary':
                    $answer = $change->getSecretaryAnswer();
                    break;
            }
            if ($answer == 0) {
             $wait = true;
            } else {
                if ($answer == 2) {
                    $allowed++;
                } else {
                    $disallowed++;
                }
            }
        }

        if (!$wait) {
    if ($allowed > 0 && $disallowed == 0) {
        $result = 2;
    } elseif ($allowed > 0 && $disallowed > 0) {
        $result = 3;
    } elseif ($allowed == 0 && $disallowed>0) {
        $result = 1;
    }
    } else {
        $result = 0;
        }

    return $result;
    }
}
