<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Student',
	array(
		'Project' => 'studentList, show, new, create, edit, update, delete, projectStatusAdjust',
		'Change' => 'show, new, create, edit, update, delete',
		
	),
	// non-cacheable actions
	array(
		'Project' => 'studentList, show, new, create, edit, update, delete, projectStatusAdjust',
		'Change' => 'show, new, create, edit, update, delete',
		
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Teacher',
	array(
		'Project' => 'teacherList',
		'Change' => 'teacherAnswer, teacherAnswerCompletion',

	),
	// non-cacheable actions
	array(
		'Project' => 'teacherList',
		'Change' => 'teacherAnswer, teacherAnswerCompletion',

	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Secretary',
	array(
		'Project' => 'secretaryList, secretaryShow, secretaryChange, delete, secretaryPrint',
		'Change' => 'secretaryShow, secretaryProcess, secretaryProcessCompletion, findRoom',
		
	),
	// non-cacheable actions
	array(
		'Project' => 'secretaryList, secretaryShow, secretaryChange, delete, secretaryPrint',
		'Change' => 'secretaryShow, secretaryProcess, secretaryProcessCompletion, findRoom',
		
	)
);
