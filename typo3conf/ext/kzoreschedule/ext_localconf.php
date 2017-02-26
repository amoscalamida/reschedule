<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Student',
	array(
		'Project' => 'studentList, show, new, create, edit, update, delete, projectStatusAdjust, assistantSearch',
		'Change' => 'show, new, create, edit, update, delete, courseDetails',
		
	),
	// non-cacheable actions
	array(
		'Project' => 'studentList, show, new, create, edit, update, delete, projectStatusAdjust, assistantSearch',
		'Change' => 'show, new, create, edit, update, delete, courseDetails',
		
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Teacher',
	array(
		'Project' => 'teacherContainer,teacherList',
		'Change' => 'teacherAnswer, teacherAnswerCompletion,courseDetails',

	),
	// non-cacheable actions
	array(
		'Project' => 'teacherContainer,teacherList',
		'Change' => 'teacherAnswer, teacherAnswerCompletion',

	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Secretary',
	array(
		'Project' => 'secretaryList, secretaryShow, secretaryChange, delete, secretaryPrint',
		'Change' => 'secretaryShow, secretaryProcess, secretaryProcessCompletion, findRoom, courseDetails',
		
	),
	// non-cacheable actions
	array(
		'Project' => 'secretaryList, secretaryShow, secretaryChange, delete, secretaryPrint',
		'Change' => 'secretaryShow, secretaryProcess, secretaryProcessCompletion, findRoom',
		
	)
);

