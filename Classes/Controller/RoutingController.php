<?php
namespace Fab\OaiServer\Controller;

/*
 * This file is part of the Fab/OaiServer project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Utility\EidUtility;

/**
 * Routing controller.
 */
class RoutingController
{

    /**
     * @param string $verb
     */
    public function __construct($verb)
    {
        // Tweak, inject parameters
        $_GET['tx_oaiserver_pi1']['verb'] = $verb;
    }

    /**
     * Dispatches the request and returns data.
     *
     * @return string
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function dispatch()
    {
        $this->initializeFrontend();

        /** @var Bootstrap $bootstrap */
        $bootstrap = $this->getObjectManager()->get(Bootstrap::class);

        $configuration = [
            'pluginName' => 'Pi1',
            'extensionName' => 'OaiServer',
            'vendorName' => 'Fab',
        ];

        return $bootstrap->run('', $configuration);
    }


    /**
     * Initializes TSFE and $GLOBALS['TSFE'].
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function initializeFrontend()
    {
        $pageId = GeneralUtility::_GP('id');
        /** @var TypoScriptFrontendController $tsfe */
        $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            $GLOBALS['TYPO3_CONF_VARS'],
            $pageId,
            ''
        );

        EidUtility::initLanguage();
        EidUtility::initTCA();

        $GLOBALS['TSFE']->initFEuser();
        // We do not want (nor need) EXT:realurl to be invoked:
        //$GLOBALS['TSFE']->checkAlternativeIdMethods();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
        if ($pageId > 0) {
            $GLOBALS['TSFE']->settingLanguage();
        }
        $GLOBALS['TSFE']->settingLocale();

        // Get linkVars, absRefPrefix, etc
        //\TYPO3\CMS\Frontend\Page\PageGenerator::pagegenInit();
    }

    /**
     * @return ObjectManager
     * @throws \InvalidArgumentException
     */
    protected function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

}

$output = '404'; // @todo 500
$verb = GeneralUtility::_GET('verb');

if ($verb) {

    /** @var RoutingController $routing */
    $routing = GeneralUtility::makeInstance(RoutingController::class, $verb);

    try {
        $output = $routing->dispatch();
    } catch (\Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        print <<<HTML
<!DOCTYPE html>
<html><head>
<title>500 Internal Server Error</title>
</head><body>
<h1>500 Internal Server Error</h1>
<p>Error {$e->getCode()}: {$e->getMessage()}</p>
<hr>
<address>OAI server at {$_SERVER['SERVER_NAME']}</address>
</body></html>
HTML;
        exit();
    }
}

if ($output === '403') {
    header('403 Not Found');
    print <<<HTML
<!DOCTYPE html>
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>403 Forbidden</h1>
<p>You do not have access to this resource {$_SERVER['REQUEST_URI']}.</p>
<hr>
<address>OAI server at {$_SERVER['SERVER_NAME']}</address>
</body></html>
HTML;
    exit();
} elseif ($output === '404') {
    header('404 Not Found');
    print <<<HTML
<!DOCTYPE html>
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>No resource {$_SERVER['REQUEST_URI']} to display on this server.</p>
<hr>
<address>OAI server at {$_SERVER['SERVER_NAME']}</address>
</body></html>
HTML;
    exit();
} else {
    print $output;
}
