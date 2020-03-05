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
     * @param string $verb
     * @return Settings
     * @throws \InvalidArgumentException
     */
    public function resolve($verb)
    {

        /** @var Settings $settings */
        $settings = GeneralUtility::makeInstance(Settings::class);
        $settings->setVerb($verb);

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

        if (array_key_exists('limit', $this->tsSettings)) {
            $settings->setLimit((int)$this->tsSettings['limit']);
        }
        return $settings;
    }

}
