<?php
namespace Fab\OaiServer\ViewHelpers\Result;

/*
 * This file is part of the Fab/OaiServer project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\OaiServer\Resolver\ContentResolver;
use Fab\OaiServer\Resolver\Settings;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Abstract View helper for rendering an Export request.
 */
abstract class AbstractToFormatViewHelper extends AbstractViewHelper
{

    /**
     * @return array
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    protected function getItems()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');
        $objects = $object = $this->templateVariableContainer->get('objects');

        // Take the first item for the one
        if ($settings->getManyOrOne() === Settings::MANY) {
            $items = $this->getContentResolver()->getItems($objects);
        } else {
            $items[] = $this->getContentResolver()->getItem($object);
        }

        return $items;
    }

    /**
     * @return ContentResolver
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    protected function getContentResolver()
    {
        $settings = $this->templateVariableContainer->get('settings');
        return GeneralUtility::makeInstance(ContentResolver::class, $settings);
    }

}
