<?php
namespace Fab\OaiServer\Resolver;

/*
 * This file is part of the Fab/OaiServer project under GPLv2 or later.
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
        if (array_key_exists('mappings', $settings) && is_array($settings['mappings'])) {
            $this->typoscript = $settings;
        } else {
            throw new \RuntimeException('Mapping configuration missing. Make sure the TypoScript settings is correctly loaded for the OAI server', 1472451633);
        }
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

        $aliasDataType = $settings->getFistRouteSegment();

        if (array_key_exists($aliasDataType, $this->typoscript['mappings'])) {
            $mappings = $this->typoscript['mappings'][$aliasDataType];

            if (array_key_exists('tableName', $mappings)) {
                $settings->setContentType((string)$mappings['tableName']);
            }

            if (array_key_exists('excludedFields', $mappings)) {
                $settings->setExcludedFields(GeneralUtility::trimExplode(',', $mappings['excludedFields'], true));
            }

            if (array_key_exists('orderings', $mappings) && is_array($mappings['orderings'])) {
                $settings->setOrderings($mappings['orderings']);
            }

            if (array_key_exists('limit', $mappings)) {
                $settings->setLimit((int)$mappings['limit']);
            }

            if ($settings->countRouteSegments() === 2) {
                $settings->setManyOrOne(Settings::ONE);
            } else {
                $settings->setManyOrOne(Settings::MANY);
            }

            if (array_key_exists($settings->getManyOrOne(), $mappings) && array_key_exists('fields', $mappings[$settings->getManyOrOne()])) {
                $fieldList = $mappings[$settings->getManyOrOne()]['fields'];
                $settings->setFields(GeneralUtility::trimExplode(',', $fieldList, true));
            }

            if (array_key_exists('permissions', $mappings)) {
                $permissions = $mappings['permissions'];
                if (array_key_exists('frontendUserGroups', $permissions)) {
                    $settings->setPermissionsUserGroups(GeneralUtility::trimExplode(',', $permissions['frontendUserGroups'], true));
                }

                if (array_key_exists('token', $permissions)) {
                    $settings->setPermissionToken((string)$permissions['token']);
                }
            }

            // Override specific configuration for format output (atom, csv, ...)
            if (array_key_exists($settings->getFormat(), $this->typoscript)) {
                $specific = $this->typoscript[$settings->getFormat()];

                if (array_key_exists('limit', $specific)) {
                    $settings->setLimit((int)$specific['limit']);
                }
            }

        }

        return $settings;
    }

}
