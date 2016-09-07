<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Possible Static TS loading
$configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['oai_server']);
if (true === isset($configuration['autoload_typoscript']['value']) && true === (bool)$configuration['autoload_typoscript']['value']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('oai_server', 'Configuration/TypoScript', 'OAI server: expose content in various formats');
}