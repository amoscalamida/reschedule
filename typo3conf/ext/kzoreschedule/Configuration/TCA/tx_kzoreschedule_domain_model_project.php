<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_project',
		'label' => 'user_id',
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
		'searchFields' => 'user_id,title,comment,progress,changes,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('kzoreschedule') . 'Resources/Public/Icons/tx_kzoreschedule_domain_model_project.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, user_id, title, comment, progress, changes',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, user_id, title, comment, progress, changes, '),
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
				'foreign_table' => 'tx_kzoreschedule_domain_model_project',
				'foreign_table_where' => 'AND tx_kzoreschedule_domain_model_project.pid=###CURRENT_PID### AND tx_kzoreschedule_domain_model_project.sys_language_uid IN (-1,0)',
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

		'user_id' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_project.user_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'title' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_project.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'comment' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_project.comment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim, required'
			)
		),
		'progress' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_project.progress',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'changes' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:kzoreschedule/Resources/Private/Language/locallang_db.xlf:tx_kzoreschedule_domain_model_project.changes',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_kzoreschedule_domain_model_change',
				'foreign_field' => 'project',
				'foreign_sortby' => 'changed_lesson',
				'maxitems' => 9999,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
					'showPossibleLocalizationRecords' => 1,
					'showAllLocalizationLink' => 1
				),
			),

		),
		
	),
);