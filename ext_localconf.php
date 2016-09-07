<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

//# No cachable plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Fab.oai_server',
    'Pi1',
    array(
        'OaiServer' => 'output',
    ),
    // non-cacheable actions
    array(
        'OaiServer' => 'output',
    )
);

// Define whether to automatically load TS.
$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['oai_server']);
if (false === isset($configuration['autoload_typoscript']) || true === (bool)$configuration['autoload_typoscript']) {

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'oai_server',
        'constants',
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:oai_server/Configuration/TypoScript/constants.txt">'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
        'oai_server',
        'setup',
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:oai_server/Configuration/TypoScript/setup.txt">'
    );
}

// Register routing service
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['oai_server'] = 'EXT:oai_server/Classes/Controller/RoutingController.php';