<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

$configuration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
    \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
)->get('doi_system');

if (!isset($configuration['autoload_typoscript']) || true === (bool)$configuration['autoload_typoscript']) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('doi_system', 'Configuration/TypoScript', 'DOI system');
}
