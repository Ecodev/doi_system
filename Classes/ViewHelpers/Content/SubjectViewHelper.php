<?php
namespace Fab\DoiSystem\ViewHelpers\Content;

/*
 * This file is part of the Fab/DoiSystem project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class SubjectViewHelper
 */
class SubjectViewHelper extends AbstractViewHelper
{

    /**
     * @return string
     */
    public function render()
    {
        $file = $this->templateVariableContainer->get('file');
        return str_replace(',', ';', $file['metadata']['keywords']);
    }

}
