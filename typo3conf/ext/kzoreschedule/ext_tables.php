<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Student',
	'Student Module'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Teacher',
	'Teacher Module'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'AmosCalamida.' . $_EXTKEY,
	'Secretary',
	'Secretary Module'
);

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'AmosCalamida.' . $_EXTKEY,
		'tools',	 // Make module a submodule of 'tools'
		'admin',	// Submodule key
		'',						// Position
		array(
			'Project' => 'adminList, show, new, create, edit, update, deleteAdmin, deleteAll',
			'Change' => 'list, show, new, create, edit, update, courseDetails',
			
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon_white.png',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_admin.xlf',
		)
	);

}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'reSchedule KZO');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kzoreschedule_domain_model_project', 'EXT:kzoreschedule/Resources/Private/Language/locallang_csh_tx_kzoreschedule_domain_model_project.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kzoreschedule_domain_model_project');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_kzoreschedule_domain_model_change', 'EXT:kzoreschedule/Resources/Private/Language/locallang_csh_tx_kzoreschedule_domain_model_change.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_kzoreschedule_domain_model_change');
