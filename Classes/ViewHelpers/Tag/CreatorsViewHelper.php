<?php
namespace Fab\DoiSystem\ViewHelpers\Tag;

/*
 * This file is part of the Fab/DoiSystem project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\DoiSystem\Resolver\Settings;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class CreatorsViewHelper
 */
class CreatorsViewHelper extends AbstractViewHelper
{

    /**
     * @return string
     */
    public function render()
    {
        $file = $this->templateVariableContainer->get('file');

        $creators = GeneralUtility::trimExplode(';', $file['metadata']['creator'], true);

        $output = [];
        foreach ($creators as $creator) {
            $output[] = sprintf('<dc:creator>%s</dc:creator>', $creator);
        }

        return implode("\n\t\t\t\t\t\t", $output);
    }

}
