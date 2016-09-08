<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

//# No cachable plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Fab.doi_system',
    'Pi1',
    array(
        'DoiSystem' => 'output',
    ),
    // non-cacheable actions
    array(
        'DoiSystem' => 'output',
    )
);

// Define whether to automatically load TS.
$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['doi_system']);
if (false === isset($configuration['autoload_typoscript']) || true === (bool)$configuration['autoload_typoscript']) {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'doi_system',
        'constants',
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:doi_system/Configuration/TypoScript/constants.txt">'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'doi_system',
        'setup',
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:doi_system/Configuration/TypoScript/setup.txt">'
    );
}

// Register routing service
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['doi_system'] = 'EXT:doi_system/Classes/Controller/RoutingController.php';