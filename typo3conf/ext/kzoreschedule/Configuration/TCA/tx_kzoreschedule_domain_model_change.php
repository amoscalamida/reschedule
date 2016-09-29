<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change',
		'label' => 'course_id',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',

		'enablecolumns' => array(

		),
		'searchFields' => 'course_id,original_lesson,changed_lesson,secretary_answer,secretary_comment,room,teacher_answer,teacher_comment,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('kzoreschedule') . 'Resources/Public/Icons/tx_kzoreschedule_domain_model_change.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, course_id, original_lesson, changed_lesson, secretary_answer, secretary_comment, room, teacher_answer, teacher_comment',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, course_id, original_lesson, changed_lesson, secretary_answer, secretary_comment, room, teacher_answer, teacher_comment, '),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_kzoreschedule_domain_model_change',
				'foreign_table_where' => 'AND tx_kzoreschedule_domain_model_change.pid=###CURRENT_PID### AND tx_kzoreschedule_domain_model_change.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),

		'course_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.course_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			)
		),
		'original_lesson' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.original_lesson',
			'config' => array(
				'dbType' => 'datetime',
				'type' => 'input',
				'size' => 12,
				'eval' => 'datetime,required',
				'default' => '0000-00-00 00:00:00'
			),
		),
		'changed_lesson' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.changed_lesson',
			'config' => array(
				'dbType' => 'datetime',
				'type' => 'input',
				'size' => 12,
				'eval' => 'datetime,required',
				'default' => '0000-00-00 00:00:00'
			),
		),
		'secretary_answer' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.secretary_answer',
			'config' => array(
				'type' => 'input',
				'default' => 0
			)
		),
		'secretary_comment' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.secretary_comment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'room' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.room',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'teacher_answer' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.teacher_answer',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'teacher_comment' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_change.teacher_comment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		
		'project' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
	),
);