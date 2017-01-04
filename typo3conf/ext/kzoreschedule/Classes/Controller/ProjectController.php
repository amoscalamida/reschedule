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

use TYPO3\CMS\Core\Database\DatabaseConnection;

setlocale(LC_ALL, 'de_CH.UTF-8');


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
     * Property for accessing DatabaseConnection centrally
     *
     * @var DatabaseConnection
     */
    protected $databaseConnection;


    /**
     * Constructor Function for setting up DB Connection
     *
     * @param DatabaseConnection $databaseConnection
     */

    function __construct(DatabaseConnection $databaseConnection=null)
    {

        $this->databaseConnection = $databaseConnection ?: $GLOBALS['TYPO3_DB'];

    }

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
     * @ignorevalidation $project
     * @return void
     */
    public function showAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $disagree = 0;
        $secretaryTouches = 0;
        foreach ($project->getChanges() as $change) {

            if ($change->getTeacherAnswer() == 0 OR $change->getTeacherAnswer() == 1) {
                $disagree++;
            }
            if ($change->getSecretaryAnswer() != 0) {
                $secretaryTouches++;
            }

        }
        ($disagree == 0) ? $teachersAgree = true : $teachersAgree = false;
        ($secretaryTouches == 0) ? $secretaryDidNotTouch = true : $secretaryDidNotTouch = false;

        $project->setUserId($this->getUserInfo($project->getUserId(),"name"));
        $this->view->assign('allTeachersAgree', $teachersAgree);
        $this->view->assign('secretaryDidNotTouch', $secretaryDidNotTouch);
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
        $project->setUserId($this->getUserInfo($project->getUserId(),"name,zip"));
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
        $this->redirect('show', NULL, NULL, array('project' => $newProject));
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
        $this->redirect('show', NULL, NULL, array('project' => $project));
    }

    /**
     * action delete
     *
     * remove the project and all of its changes from repository
     *
     * @param \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @ignorevalidation $project
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
     * @param string $modification - modification to perform (either open or close)
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
                $project->setProgress("3");
                $this->projectRepository->update($project);
                $this->sendNotificationEmail($project, "closeproject");
                foreach ($project->getChanges() as $change) {
                    $this->sendNotificationEmail($change, "closeproject");
                }
                $this->addFlashMessage("Projekt erfolgreich geschlossen. Betroffene Personen wurden per Mail benachrichtigt");
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
        $this->redirect("show", NULL, NULL, array('project' => $project));

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

        $projects = $this->projectRepository->findAll();
        $projectsCount = count($projects);

        foreach ($projects as $project) {
            $this->projectRepository->remove($project);
        }

        $this->addFlashMessage("Es wurden " . $projectsCount . " Projekte gelöscht");
        $this->redirect("adminList");
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

        $secretaryAnswers = array();
        $teacherAnswers = array();
        foreach ($projects as $project) {

            array_push($secretaryAnswers, $this->getAnswerProgress($project, "secretary"));
            array_push($teacherAnswers, $this->getAnswerProgress($project, "teacher"));

        }

        $this->view->assign('secretaryAnswers', $secretaryAnswers);
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
        foreach ($projects as $project) {
            $project->setUserId($this->getUserInfo($project->getUserId(),"name"));
        }
        $this->view->assign('projects', $projects);
    }

    /**
     * action teacherContainer
     *
     * prepare container for teacher view
     *
     * @param integer $filter - filter for results (either 0: unanswered or 1: answered)
     * @return void
     */
    public function teacherContainerAction($filter = 0)
    {

        switch ($filter) {
            case 0:
                $divfilter = 1;
                break;
            case 1:
                $divfilter = 0;
                break;
        }
        $this->view->assign("divfilter",$divfilter);

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
        $teachersCourses = getTeacherCourses($GLOBALS['TSFE']->fe_user->user['title']);
        $projects = $this->projectRepository->findByPublicAndCourses($teachersCourses);
        $allowed_changes = array();
        $allowed_changes_project = array();
        foreach ($projects as $project) {
            if ($project->getProgress() > 0) {
                $project->setUserId($this->getUserInfo($project->getUserId(),"name"));
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

        $secretaryAnswers = array();
        foreach ($projects as $project) {
            $project->setUserId($this->getUserInfo($project->getUserId(),"name,zip"));
            array_push($secretaryAnswers, $this->getAnswerProgress($project, "secretary"));

        }

        $this->view->assign('secretaryAnswers', $secretaryAnswers);
        $this->view->assign('divprogress', $divprogress);
        $this->view->assign('projects', $projects);

    }


    /**
     * action secretaryPrint
     *
     * list projects for sectretary view
     *
     * @param $project \AmosCalamida\Kzoreschedule\Domain\Model\Project $project
     * @return void
     */
    public function secretaryPrintAction(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project)
    {
        $project->setUserId($this->getUserInfo($project->getUserId(),"name,zip"));
        $this->view->assign('project', $project);
    }


    /**
     * function sendNotificationEmail
     *
     * sends an email to teacher or student with outcome of project or change
     *
     * @param object $object - change or project to notify
     * @param  string $template - the template name defining the message content
     *                            (possible values: "closeproject","updateproject","releaseproject"
     *
     * @return void
     */
    public static function sendNotificationEmail($object, $template = "Auto")
    {

        switch (get_class($object)) {

            case 'AmosCalamida\Kzoreschedule\Domain\Model\Change':
                //passed object is Change
                switch ($template) {

                    case "closeproject":

                        switch ($object->getSecretaryAnswer()) {

                            case 1: //change denied
                                $accentColor = "#d9534f";
                                $answer = 0;
                                break;

                            case 2: //change accepted
                                $accentColor = "#5cb85c";
                                $answer = 1;
                                break;

                        }

                        $preheader = "Die Verschiebung Ihrer Lektion wurde vom Sekretariat ".(($answer==0)?"nicht bewilligt":"bewilligt").".";
                        $intro = "Die Stundenverschiebung Ihrer Lektion <b>". getCourseDetails($object->getCourseId(),"teacher","label") . "</b> wird <span style='color: $accentColor'>".(($answer==0)?'nicht durchgeführt':'durchgeführt')."</span>. Ihre Lektion findet ".(($answer==0)?'normal':'nun')." am <b>" . (($answer==0)?$object->getOriginalLesson()->format("d.m.Y H:i"):$object->getChangedLesson()->format("d.m.Y H:i")) . " Uhr</b> ".(($object->getRoom()=="")?"statt. ".(($answer==0)?'':'Das ev. geänderte Zimmer entnehmen Sie bitte dem Stundenplan.'):"im <b>Zimmer " . $object->getRoom() . "</b> statt.");
                        $comment = $object->getSecretaryComment();
                        $outro = (($comment != "") ? "<b>Kommentar/Auflage des Sekretariats:</b><br><i>" . $comment . "</i><br><br>" : "<br>") . "Freundliche Grüsse";

                        $from = \TYPO3\CMS\Core\Utility\MailUtility::getSystemFrom();
                        $to = array("amos.calamida@me.com" => "Amos Calamida");
                        $body = self::getEmailDesign(array($preheader, $intro, $outro));
                        $subject = "Entscheid der Stundenverschiebung";

                        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
                        $mail->setFrom($from)
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setBody($body, 'text/html')
                            ->send();

                        break;

                    case "updateproject":


                        break;

                    case "releaseproject":


                        break;


                }


                break;

            case 'AmosCalamida\Kzoreschedule\Domain\Model\Project':
                //passed object is Project

                switch ($template) {

                    case "closeproject":

                        $changeList = "<ul>";

                        foreach ($object->getChanges() as $change) {

                            switch ($change->getSecretaryAnswer()) {

                                case 1: //change denied
                                    $accentColor = "#d9534f";
                                    $comment = $change->getSecretaryComment();
                                    $changeList .= "<li>" . getCourseDetails($change->getCourseId(),"student","label") . ": <span style='color: $accentColor' >nicht bewilligt</span>".(($comment != "") ? "<br><br><b>Kommentar/Auflage des Sekretariats: </b><i>" . $comment . "</i><br><br>" : "")."</li>";
                                    break;

                                case 2: //change accepted
                                    $accentColor = "#5cb85c";
                                    $comment = $change->getSecretaryComment();
                                    $changeList .= "<li>" . getCourseDetails($change->getCourseId(),"student","label") . ": <span style='color: $accentColor' >bewilligt</span>".(($comment != "") ? "<br><br><b>Kommentar/Auflage des Sekretariats: </b><i>" . $comment . "</i><br><br></b>" : "")."</li>";
                                    break;

                            }
                        }

                        $changeList .= "</ul>";

                        $preheader = "Das Projekt wurde vom Sekretariat abgeschlossen.";
                        $intro = "Das eingereichte Projekt \"". $object->getTitle()."\" wurde vom Sekretariat geprüft. Folgender Entscheid wurde getroffen: <br><br> $changeList";
                        $outro = "Freundliche Grüsse";

                        $from = \TYPO3\CMS\Core\Utility\MailUtility::getSystemFrom();
                        $to = array("amos.calamida@me.com" => "Amos Calamida");
                        $body = self::getEmailDesign(array($preheader, $intro, $outro));
                        $subject = $object->getTitle() . ": Entscheid des Sekretariats";


                        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
                        $mail->setFrom($from)
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setBody($body, 'text/html')
                            ->send();

                        break;

                    case "updateproject":

                        $changeList = "<ul>";

                        foreach ($object->getChanges() as $change) {

                            switch ($change->getTeacherAnswer()) {

                                case 1: //change not answered
                                    $accentColor = "#f0ad4e";
                                    $changeList .= "<li>" . getCourseDetails($change->getCourseId(),"student","label") . ": <span style='color: $accentColor' >Warte auf Antwort</span></li>";
                                    break;

                                case 1: //change denied
                                    $accentColor = "#d9534f";
                                    $comment = $change->getTeacherComment();
                                    $changeList .= "<li>" . getCourseDetails($change->getCourseId(),"student","label") . ": <span style='color: $accentColor' >Nein</span>".(($comment != "") ? " | <i>" . $comment . "</i>" : "")."</li>";
                                    break;

                                case 2: //change accepted
                                    $accentColor = "#5cb85c";
                                    $comment = $change->getTeacherComment();
                                    $changeList .= "<li>" . getCourseDetails($change->getCourseId(),"student","label") . ": <span style='color: $accentColor' >Ja</span>".(($comment != "") ? " | <i>" . $comment . "</i>" : "")."</li>";
                                    break;

                            }
                        }

                        $changeList .= "</ul>";

                        $preheader = "Eine Lehrperson hat soeben ihre Verschiebung im Projekt ".$object->getTitle()." beantwortet.";
                        $intro = "Eine Verschiebung im Projekt \"". $object->getTitle()."\" wurde soeben von der Lehrperson beantwortet. Die aktuelle Übersicht ist hier ersichtlich: <br><br> $changeList";
                        $outro = "Freundliche Grüsse";

                        $from = \TYPO3\CMS\Core\Utility\MailUtility::getSystemFrom();
                        $to = array("amos.calamida@me.com" => "Amos Calamida");
                        $body = self::getEmailDesign(array($preheader, $intro, $outro));
                        $subject = $object->getTitle() . ": Entscheid des Sekretariats";


                        $mail = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Mail\\MailMessage');
                        $mail->setFrom($from)
                            ->setTo($to)
                            ->setSubject($subject)
                            ->setBody($body, 'text/html')
                            ->send();

                        break;

                    case "releaseproject":



                        break;


                }
                break;

        }
    }


    /**
     * function getEmailDesign
     *
     * returns the email layout with incorporated message
     *
     * @param array $message - the message divided in parts: 0 => preheader (preview text), 1 => intro (before button), 2 => outro (after button)
     *
     * @return string - html for email
     */

    public static function getEmailDesign($message)
    {

        $preheader = $message[0];
        $intro = $message[1];
        $outro = $message[2];

        $css = '<style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
        max-width: 100%; }
      body {
            background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0; 
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }
      table {
            border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
            font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }
      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */
      .body {
            background-color: #f6f6f6;
        width: 100%; }
      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
            display: block;
            Margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; }
      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
            box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        max-width: 580px;
        padding: 10px; }
      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
            background: #fff;
            border-radius: 3px;
        width: 100%; }
      .wrapper {
            box-sizing: border-box;
        padding: 20px; }
      .footer {
            clear: both;
            padding-top: 10px;
        text-align: center;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
          text-align: center; }
      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
            color: #000000;
            font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }
      h1 {
            font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }
      p,
      ul,
      ol {
            font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
            list-style-position: inside;
          margin-left: 5px; }
      a {
            color: #3498db;
            text-decoration: underline; }
      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
            box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
            padding-bottom: 15px; }
        .btn table {
            width: auto; }
        .btn table td {
            background-color: #ffffff;
          border-radius: 5px;
          text-align: center; }
        .btn a {
            background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
           }
      .btn-primary table td {
            background-color: #3498db; }
      .btn-primary a {
                background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }
      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
                    margin-bottom: 0; }
      .first {
                    margin-top: 0; }
      .align-center {
                    text-align: center; }
      .align-right {
                    text-align: right; }
      .align-left {
                    text-align: left; }
      .clear {
                    clear: both; }
      .mt0 {
                    margin-top: 0; }
      .mb0 {
                    margin-bottom: 0; }
      .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }
      .powered-by a {
                    text-decoration: none; }
      hr {
                    border: 0;
                    border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }
      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
                    table[class=body] h1 {
                        font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
                        font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
                        padding: 10px !important; }
        table[class=body] .content {
                        padding: 0 !important; }
        table[class=body] .container {
                        padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
                        border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
                        width: 100% !important; }
        table[class=body] .btn a {
                        width: 100% !important; }
        table[class=body] .img-responsive {
                        height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}
      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
                    .ExternalClass {
                        width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
                        line-height: 100%; }
        .apple-link a {
                        color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; } 
        .btn-primary table td:hover {
                        background-color: #34495e !important; }
        .btn-primary a:hover {
                            background-color: #34495e !important;
          border-color: #34495e !important; } }
    </style>';

        $head = '<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Benachrichtigung</title>' . $css . '</head>';

        $body = '
        
        <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader">' . $preheader . '</span>
            <table class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p>Hallo,</p>
                        <p>' . $intro . '</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                        <!-- TODO: change URL for production environment -->
                                      <td> <a href="http://webmast9.myhostpoint.ch/t/index.php?id=38" target="_blank">Auf dem Intranet öffnen</a> </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <p>' . $outro . '</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- END MAIN CONTENT AREA -->
              </table>

            <!-- START FOOTER -->
            <div class="footer">
              <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-block">
                    <span class="apple-link">Diese E-Mail wurde automatisch vom KZO Intranet aus versendet.</span>
                    <br> Fragen können an das <a href="mailto:it-team@kzo.ch">IT-Team</a> gerichtet werden.
                  </td>
                </tr>
              </table>
            </div>

            <!-- END FOOTER -->
            
<!-- END CENTERED WHITE CONTAINER --></div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
  
        ';


        $html = "
        
        <!doctype html>
        <html>
        $head
        $body
        </html>
        ";


        return $html;

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
    public function getAnswerProgress(\AmosCalamida\Kzoreschedule\Domain\Model\Project $project, $category)
    {

        $allowed = 0;
        $disallowed = 0;

        foreach ($project->getChanges() as $change) {
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
            } elseif ($allowed == 0 && $disallowed > 0) {
                $result = 1;
            }
        } else {
            $result = 0;
        }

        return $result;
    }

    /**
     * Deactivate errorFlashMessage by overwriting Extbase method.
     * Important for translated error messages
     *
     * @return bool
     */
    public function getErrorFlashMessage()
    {
        return FALSE;
    }


    /**
     * function getUserInfo
     * Get all information about any FE User
     *
     *
     * @param int $id - id of FE_User
     * @param string $info - columns to get
     *
     * @return string
     */

    public function getUserInfo($id,$info = "*") {
        $data = $this->databaseConnection->fullQuoteStr($id, 'fe_users');
        $res = $this->databaseConnection->exec_SELECTquery(
            $info,
            'fe_users',
            '(uid=' . $data.')'
        );
        $row = $this->databaseConnection->sql_fetch_assoc($res);
       if ($info!="*"){
           $columns = explode(",",$info);
           return ((count($columns)!=1)?$row:$row[$info]);
        }
        else {
            return $row;
        }
    }


}

/**
 * function generateHeaders
 *
 * Generate Headers for Timetable REST Access
 *
 * @param string $username
 * @param string $password
 * @param string $prefix
 * @param string $hashAlgorithm
 * @return array
 */

function generateHeaders($username, $password, $prefix, $hashAlgorithm = 'sha1')
{

    $rfc_1123_date = gmdate('D, d M Y H:i:s T', time());
    $xgrdate = utf8_encode($rfc_1123_date);
    $userPasswd = base64_encode(hash($hashAlgorithm, $password, true));

    $signature = base64_encode(hash_hmac($hashAlgorithm, $userPasswd, $xgrdate));
    $auth = $prefix . " " . base64_encode($username) . ":" . $signature;
    $headers = array(
        'X-gr-AuthDate' => $xgrdate,
        'Authorization' => $auth
    );


    return $headers;


}

/**
 * function getTeacherCourses
 * Get all courses given by a teacher
 *
 * @param string $teacher - the short form of the teachers name
 * @return array
 */


function getTeacherCourses($teacher)
{
    $conditions = array('WHERE' => array("Teacher" => "$teacher"), 'ORDER' => 'Class');
    $mod = base64_encode(json_encode($conditions));

    $user = 'rest-timetable';
    $pwd = 'a46vQJzsY9YwVhKCxjUD';
    $controller = 'data/source/timetable';
    $school = 'kzo';

    $headers = generateHeaders($user, $pwd, 'gr001');

    $request = "https://api.tam.ch/$school/$controller?mod=$mod";

    $result = exec("curl " . $request


        . ' -H "Accept:application/xml"'


        . ' -H "X-gr-AuthDate:' . $headers['X-gr-AuthDate'] . '"'


        . ' -H "Authorization:' . $headers['Authorization'] . '"'

    );

    $json = json_decode($result)->body;

    $teacher_courses = array();
    function checkArrayForObject($array, $id)
    {
        return array_filter($array, function ($object) use ($id) {
            return $object->ID == $id;
        });
    }

    foreach ($json as $course) {
        if (!in_array($course->ID, $teacher_courses)) {
            array_push($teacher_courses, $course->ID);
        }
    }

    return $teacher_courses;

}

/**
 * function getCourseDetails
 * Get all courses given by a teacher
 *
 * @param int $id - the id of the course
 * @param string $mode - either teacher, student or secretary (changes returned details)
 * @param string $req - the requested information (either "label", "teacher", "class")
 * @return string
 */

function getCourseDetails($id, $mode, $req)
{
    $conditions = array('WHERE' => array("ID" => "$id"), 'ORDER' => 'Subject');
    $mod = base64_encode(json_encode($conditions));

    $user = 'rest-timetable';
    $pwd = 'a46vQJzsY9YwVhKCxjUD';
    $controller = 'data/source/timetable';
    $school = 'kzo';

    $headers = generateHeaders($user, $pwd, 'gr001');

    $request = "https://api.tam.ch/$school/$controller?mod=$mod";

    $result = exec("curl " . $request


        . ' -H "Accept:application/xml"'


        . ' -H "X-gr-AuthDate:' . $headers['X-gr-AuthDate'] . '"'


        . ' -H "Authorization:' . $headers['Authorization'] . '"'

    );

    $json = json_decode($result)->body;

    $course = $json[0];
    $subjects = array("T" => "Sport", "M" => "Mathematik", "D" => "Deutsch", "B" => "Biologie", "P" => "Physik", "BG" => "Bildnerisches Gestalten", "RL" => "Religion", "GR" => "Griechisch", "L" => "Lateinisch", "SP" => "Spanisch", "IT" => "Italienisch", "G" => "Geschichte", "GG" => "Geografie", "MU" => "Musik", "E" => "Englisch", "F" => "Französisch", "C" => "Chemie", "AM" => "Angewandte Mathematik", "AC" => "Anwendungen des Computers", "");

    switch ($mode) {
        case "secretary":
            $course->Label = $subjects[$course->Subject] . " (" . $course->Teacher . ") [" . $course->Class . "]";
            break;

        case "teacher":
            $course->Label = $subjects[$course->Subject] . " (" . $course->Class . ")";
            break;

        case "student":
            $course->Label = $subjects[$course->Subject]." (".$course->Teacher.")";
            break;
    }

    switch ($req) {
        case "label":
            return $course->Label;

            break;

        case "teacher":
            return $course->Teacher;

            break;

        case "class":
            return $course->Class;

            break;

    }

}

