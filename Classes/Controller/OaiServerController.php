<?php
namespace Fab\OaiServer\Controller;

/*
 * This file is part of the Fab/OaiServer project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\Vidi\Domain\Repository\ContentRepositoryFactory;
use Fab\Vidi\Persistence\Order;
use Fab\Vidi\Tca\Tca;
use Fab\OaiServer\Resolver\Settings;
use Fab\OaiServer\Resolver\SettingsResolver;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Fab\Vidi\Persistence\Matcher;
use TYPO3\CMS\Extbase\SignalSlot\Dispatcher;

/**
 * Class OaiServerController
 */
class OaiServerController extends ActionController
{

    /**
     * @param string $route
     * @return string
     * @throws \Fab\Vidi\Exception\InvalidKeyInArrayException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    public function outputAction($route)
    {

        $settings = $this->getSettingsResolver()->resolve($route);

        if ($settings->getContentType()) {

            $isAllowed = $this->checkPermissions($settings);
            if (!$isAllowed) {
                return '403';
            }

            $matcher = $this->getMatcher($settings);
            $order = $this->getOrder($settings);
            if ($settings->getManyOrOne() === Settings::MANY) {
                $objects = ContentRepositoryFactory::getInstance($settings->getContentType())->findBy($matcher, $order, $settings->getLimit());
            } else {
                if ($settings->getContentIdentifier() === 0) {
                    $message = sprintf('I could find a valid identifier "%s"', $settings->getContentIdentifier());
                    throw new \RuntimeException($message, 1472294542);
                }
                $matcher->equals('uid', $settings->getContentIdentifier());
                $objects = ContentRepositoryFactory::getInstance($settings->getContentType())->findOneBy($matcher);
            }

            // Early return
            if (!$objects) {
                return '404';
            }

            // Assign template variables.
            $this->view->assign('settings', $settings);
            $this->view->assign('objects', $objects);
            $this->view->assign('response', $this->controllerContext->getResponse());
        } else {
            $message = sprintf('I could find a valid content type for segment "%s"', $settings->getFistRouteSegment());
            throw new \RuntimeException($message, 1472294541);
        }

        $fileNameAndPath = 'EXT:oai_server/Resources/Private/Templates/OaiServer/Output.' . $settings->getFormat();
        $templatePathAndFilename = GeneralUtility::getFileAbsFileName($fileNameAndPath);
        $this->view->setTemplatePathAndFilename($templatePathAndFilename);

        return $this->view->render();
    }

    /**
     * @param Settings $settings
     * @return bool
     */
    protected function checkPermissions(Settings $settings)
    {
        // Default
        $isAllowed = true;

        // Check
        if ($settings->getPermissionsUserGroups()) {
            $userData = $this->getFrontendUserData();
            $userGroups = GeneralUtility::trimExplode(',', $userData['usergroups'], true);

            $isAllowed = false;

            foreach ($settings->getPermissionsUserGroups() as $userGroup) {
                if ($userGroup === '*' && $userData) {
                    $isAllowed = true;
                    break;
                } else if (in_array($userGroup, $userGroups, true)) {
                    $isAllowed = true;
                    break;
                }

            }
        }

        if ($settings->getPermissionToken() && GeneralUtility::_GET('token') !== $settings->getPermissionToken()) {
            $isAllowed = false;
        }
        return $isAllowed;
    }

    /**
     * @param Settings $settings
     * @return Matcher
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \InvalidArgumentException
     */
    protected function getMatcher(Settings $settings)
    {

        /** @var $matcher Matcher */
        $matcher = GeneralUtility::makeInstance(Matcher::class, $settings->getContentType());

        // Trigger signal for post processing Order Object.
        $this->emitPostProcessMatcherSignal($matcher);

        return $matcher;
    }

    /**
     * @param Settings $settings
     * @return Order
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \InvalidArgumentException
     */
    protected function getOrder(Settings $settings)
    {
        $orderings = $settings->getOrderings();
        if (!$orderings) {
            $orderings = Tca::table($settings->getContentType())->getDefaultOrderings();
        }

        return GeneralUtility::makeInstance(Order::class, $orderings);
    }

    /**
     * @return SettingsResolver
     * @throws \InvalidArgumentException
     */
    protected function getSettingsResolver()
    {
        return GeneralUtility::makeInstance(SettingsResolver::class, $this->settings);
    }

    /**
     * Signal that is called for post processing matcher
     *
     * @signal
     * @param Matcher $matcher
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException
     * @throws \TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException
     */
    protected function emitPostProcessMatcherSignal(Matcher $matcher)
    {
        $this->getSignalSlotDispatcher()->dispatch(self::class, 'postProcessMatcher', array($matcher));
    }

    /**
     * Get the SignalSlot dispatcher
     *
     * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected function getSignalSlotDispatcher()
    {
        return $this->objectManager->get(Dispatcher::class);
    }

    /**
     * Returns an instance of the current Frontend User.
     *
     * @return \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
     */
    protected function getFrontendUser()
    {
        return $GLOBALS['TSFE']->fe_user;
    }

    /**
     * Returns user data of the current Frontend User.
     *
     * @return array
     */
    protected function getFrontendUserData()
    {
        return $this->getFrontendUser()->user ? $this->getFrontendUser()->user : [];
    }

}