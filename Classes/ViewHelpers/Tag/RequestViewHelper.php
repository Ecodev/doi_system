<?php
namespace Fab\DoiSystem\ViewHelpers\Tag;

/*
 * This file is part of the Fab/DoiSystem project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\DoiSystem\Resolver\Settings;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class RequestViewHelper
 */
class RequestViewHelper extends AbstractViewHelper
{

    /**
     * @return string
     */
    public function render()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');
        return sprintf(
            '<request verb="%s"%s></request>',
            $settings->getVerb(),
            $this->getAdditionalArgument()
        );
    }

    /**
     * @return string
     */
    protected function getAdditionalArgument()
    {

        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');

        $additionalArgument = '';

        if ($settings->getArgument('from')) {
            $additionalArgument = sprintf(' from="%s"', $settings->getArgument('from'));
        }

        return $additionalArgument;
    }

}
