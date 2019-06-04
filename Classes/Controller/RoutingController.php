<?php
namespace Fab\DoiSystem\Controller;

/*
 * This file is part of the Fab/DoiSystem project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return string
     */
    public function dispatch(ServerRequestInterface $request, ResponseInterface $response)
    {
        $verb = GeneralUtility::_GP('verb');
        $this->initializeFrontend();

        /** @var Bootstrap $bootstrap */
        $bootstrap = $this->getObjectManager()->get(Bootstrap::class);

        $configuration = [
            'pluginName' => 'Pi1',
            'extensionName' => 'DoiSystem',
            'vendorName' => 'Fab',
        ];

        $result = $bootstrap->run('', $configuration);
        if ($result === '403') {
            $response->getBody()->write('403 Forbidden');
            $response->withStatus('403', '403 Forbidden');
        } else {
            $response->getBody()->write($result);
        }
        return $response;
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
     * @return ObjectManager|object
     */
    protected function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }

}
