<?php
namespace Fab\DoiSystem\Resolver;

/*
 * This file is part of the Fab/DoiSystem project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SettingsResolver
 */
class SettingsResolver
{
    /**
     * @var array
     */
    protected $tsSettings = [];

    /**
     * @param array $settings
     * @throws \RuntimeException
     */
    public function __construct(array $settings)
    {
        $this->tsSettings = $settings;
    }

    /**
     * @param string $route
     * @return Settings
     * @throws \InvalidArgumentException
     */
    public function resolve($route)
    {

        /** @var Settings $settings */
        $settings = GeneralUtility::makeInstance(Settings::class);
        $settings->setRouteSegments(GeneralUtility::trimExplode('/', $route));

        if (array_key_exists('filters', $this->tsSettings) && is_array($this->tsSettings['filters'])) {
            $settings->setFilters($this->tsSettings['filters']);
        }

        if (array_key_exists('permissions', $this->tsSettings)) {
            $permissions = $this->tsSettings['permissions'];
            if (array_key_exists('frontendUserGroups', $permissions)) {
                $settings->setPermissionsUserGroups(GeneralUtility::trimExplode(',', $permissions['frontendUserGroups'], true));
            }

            if (array_key_exists('token', $permissions)) {
                $settings->setPermissionToken((string)$permissions['token']);
            }
        }


//        if (array_key_exists($aliasDataType, $this->typoscript['mappings'])) {
//            $mappings = $this->typoscript['mappings'][$aliasDataType];
//
//            if (array_key_exists('tableName', $mappings)) {
//                $settings->setContentType((string)$mappings['tableName']);
//            }
//
//            if (array_key_exists('excludedFields', $mappings)) {
//                $settings->setExcludedFields(GeneralUtility::trimExplode(',', $mappings['excludedFields'], true));
//            }
//
//            if (array_key_exists('orderings', $mappings) && is_array($mappings['orderings'])) {
//                $settings->setOrderings($mappings['orderings']);
//            }
//
//            if (array_key_exists('limit', $mappings)) {
//                $settings->setLimit((int)$mappings['limit']);
//            }
//
//            if ($settings->countRouteSegments() === 2) {
//                $settings->setManyOrOne(Settings::ONE);
//            } else {
//                $settings->setManyOrOne(Settings::MANY);
//            }
//
//            if (array_key_exists($settings->getManyOrOne(), $mappings) && array_key_exists('fields', $mappings[$settings->getManyOrOne()])) {
//                $fieldList = $mappings[$settings->getManyOrOne()]['fields'];
//                $settings->setFields(GeneralUtility::trimExplode(',', $fieldList, true));
//            }
//
//            // Override specific configuration for format output (atom, csv, ...)
//            if (array_key_exists($settings->getFormat(), $this->typoscript)) {
//                $specific = $this->typoscript[$settings->getFormat()];
//
//                if (array_key_exists('limit', $specific)) {
//                    $settings->setLimit((int)$specific['limit']);
//                }
//            }
//
//        }

        return $settings;
    }

}
