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
 * Class Settings
 */
class Settings
{
    const LIST_RECORDS = 'ListRecords';

    /**
     * @var string
     */
    protected $allowedVerbs = [
        self::LIST_RECORDS
    ];

    /**
     * @var string
     */
    protected $contentType = 'sys_file';

    /**
     * @var array
     */
    protected $orderings = [];

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var array
     */
    protected $permissionsUserGroups = [];

    /**
     * @var string
     */
    protected $permissionToken = '';

    /**
     * @var string
     */
    protected $verb = '';

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     * @return $this
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderings()
    {
        return $this->orderings;
    }

    /**
     * @param array $orderings
     * @return $this
     */
    public function setOrderings($orderings)
    {
        $this->orderings = $orderings;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return array
     */
    public function getPermissionsUserGroups()
    {
        return $this->permissionsUserGroups;
    }

    /**
     * @param array $permissionsUserGroups
     * @return $this
     */
    public function setPermissionsUserGroups($permissionsUserGroups)
    {
        $this->permissionsUserGroups = $permissionsUserGroups;
        return $this;
    }

    /**
     * @return string
     */
    public function getPermissionToken()
    {
        return $this->permissionToken;
    }

    /**
     * @param string $permissionToken
     * @return $this
     */
    public function setPermissionToken($permissionToken)
    {
        $this->permissionToken = $permissionToken;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        $fromDate = $this->getArgument('from');
        if ($fromDate) {
            $fromTimeStamp = strtotime($fromDate);
            // add to correct manually the time zone
            $fromTimeStamp = $fromTimeStamp - 3600;
            $this->filters[] = 'metadata.tstamp > ' . $fromTimeStamp;
        }
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return $this
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @param string $argumentName
     * @return string
     */
    public function getArgument($argumentName)
    {
        $argumentValue = GeneralUtility::_GET($argumentName);
        return (string)$argumentValue;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->verb;
    }

    /**
     * @param string $verb
     * @return $this
     */
    public function setVerb($verb)
    {
        if (in_array($verb, $this->allowedVerbs, true)) {
            $this->verb = $verb;
        } else {
            throw new \RuntimeException('Verb "' . $verb . '" not allowed.', 1473335079);
        }
        return $this;
    }

}
