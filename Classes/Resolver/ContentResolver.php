<?php
namespace Fab\DoiSystem\Resolver;

/*
 * This file is part of the Fab/DoiSystem project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Fab\Vidi\Domain\Model\Content;
use Fab\Vidi\Tca\Tca;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class ContentResolver
 */
class ContentResolver
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param array $objects
     * @return \Fab\Vidi\Domain\Model\Content[]
     * @throws \InvalidArgumentException
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     */
    public function getItems(array $objects)
    {
        $items = [];

        foreach ($objects as $object) {
            $items[] = $this->getItem($object);
        }

        return $items;
    }

    /**
     * @return array
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     * @throws \InvalidArgumentException
     */
    public function getItem(Content $object)
    {
        $fields = array_diff($this->getFields(), $this->settings->getExcludedFields());

        $item = [];

        $resolveRelations = $this->settings->getManyOrOne() === Settings::ONE;
        $values = $resolveRelations ? $object->toValues() : $object->toArray();

        foreach ($fields as $fieldName) {
            $item[$fieldName] = $values[$fieldName];
        }

        return $item;
    }

    /**
     * @return array
     * @throws \Fab\Vidi\Exception\NotExistingClassException
     */
    public function getFields()
    {
        $fields = $this->settings->getFields();
        if (!$fields) {
            $fields = Tca::table($this->settings->getContentType())->getFields();
            $fields[] = 'uid';

            if (Tca::table($this->settings->getContentType())->getTimeModificationField()) {
                $fields[] = 'tstamp';
            }
        }
        return $fields;
    }

    /**
     * Fetch excluded fields from configuration.
     *
     * @return array
     */
    protected function getExcludedFieldsFromConfiguration()
    {
        $excludedFields = [];
        if (!empty($tca['excluded_fields'])) {
            $excludedFields = GeneralUtility::trimExplode(',', $tca['excluded_fields'], TRUE);
        }
        return $excludedFields;

    }

}
