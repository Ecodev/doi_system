<?php
namespace Fab\OaiServer\ViewHelpers\Result;

/*
 * This file is part of the Fab/OaiServer project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\Vidi\Tca\Tca;
use Fab\OaiServer\Resolver\Settings;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * View helper for rendering an ATOM response.
 */
class ToAtomViewHelper extends AbstractToFormatViewHelper
{

    /**
     * @return string
     * @throws \UnexpectedValueException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    public function render()
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');
        $entries = $this->getItems();
        $output = sprintf(
            '<?xml version="1.0"?>
<feed xmlns="http://www.w3.org/2005/Atom">

    <title>%s</title>
    <link rel="self" type="application/atom+xml" href="%s" />
    <id>%s</id>
    <updated>%s</updated>

    %s
</feed>',
            Tca::table($settings->getContentType())->getTitle(),
            GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'),
            date('c'),
            $this->renderEntries($entries)
        );

        $this->setHttpHeaders();
        return $output;
    }

    /**
     * @param array $entries
     * @return string
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected function renderEntries(array $entries)
    {

        $renderedEntries = [];
        foreach ($entries as $entry) {
            $renderedEntries[] = sprintf(
                '
    <entry xmlns="http://www.w3.org/2005/Atom">
        %s
    </entry>',
                $this->renderEntry($entry)
            );
        }

        return implode("\n", $renderedEntries);
    }

    /**
     * @param array $item
     * @return string
     * @throws \UnexpectedValueException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected function renderEntry(array $item)
    {
        /** @var Settings $settings */
        $settings = $this->templateVariableContainer->get('settings');
        return sprintf(
            '<author><name>Unknown</name></author>
        <title>%s%s</title>
        <id>%s/%s</id>
        %s
        <updated>%s</updated>
        <content type="html"><![CDATA[%s]]></content>',
            $settings->getContentType(),
            empty($item['uid']) ? uniqid('title_', true) : ' (' . $item['uid'] . ')',
            str_replace('.atom', '', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')),
            empty($item['uid']) ?: $item['uid'],
            $item['crdate'] ? '<published>' . date('c', $item['crdate']) . '</published>' : '',
            $item['tstamp'] ? date('c', $item['tstamp']) : '',
            $this->getList($item)
        );
    }

    /**
     * @param array $item
     * @return string
     * @throws \UnexpectedValueException
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     */
    protected function getList(array $item)
    {

        $list = [];

        foreach ($item as $key => $value) {
            if (!in_array($key, ['tstamp', 'uid'], true)) {
                $list[] = $key . ': ' . $value;
            }
         }

        return implode("\n, ", $list);

    }

    /**
     * @return void
     * @throws \TYPO3\CMS\Fluid\Core\ViewHelper\Exception\InvalidVariableException
     * @throws \InvalidArgumentException
     */
    protected function setHttpHeaders()
    {
        /** @var \TYPO3\CMS\Extbase\Mvc\Web\Response $response */
        $response = $this->templateVariableContainer->get('response');
        $response->setHeader('Content-Type', 'application/rss+xml');
        $response->sendHeaders();
    }

}
