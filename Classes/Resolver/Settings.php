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
 * Class Settings
 */
class Settings
{
    const LIST_RECORDS = 'ListRecords';

    /**
     * @var string
     */
    protected $contentType = 'sys_file';

    /**
     * @var int
     */
    protected $contentIdentifier = 0;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $excludedFields = [];

    /**
     * @var array
     */
    protected $orderings = [];

    /**
     * @var array
     */
    protected $routeSegments = [];

    /**
     * @var int
     */
    protected $limit = 10;

    /**
     * @var string
     */
    protected $format = 'atom';

    /**
     * @var array
     */
    protected $permissionsUserGroups = [];

    /**
     * @var string
     */
    protected $permissionToken = '';

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
     * @return int
     */
    public function getContentIdentifier()
    {
        return (int)$this->routeSegments[1];
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedFields()
    {
        return $this->excludedFields;
    }

    /**
     * @param array $excludedFields
     * @return $this
     */
    public function setExcludedFields(array $excludedFields)
    {
        $this->excludedFields = $excludedFields;
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
     * @return array
     */
    public function getRouteSegments()
    {
        return $this->routeSegments;
    }

    /**
     * @param array $routeSegments
     * @return $this
     */
    public function setRouteSegments(array $routeSegments)
    {
        $this->routeSegments = $routeSegments;

        $routeSize = count($this->routeSegments);

        // Detect the format
        if (preg_match('/([\w]+)\.(atom|csv|html|json|xml)/', $this->routeSegments[$routeSize - 1], $matches)) {
            $this->format = $matches[2];
            $this->routeSegments[$routeSize - 1] = $matches[1];
        }
        return $this;
    }

    /**
     * @return int
     */
    public function countRouteSegments()
    {
        return count($this->routeSegments);
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
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastRouteSegment()
    {
        $routeSegments = $this->routeSegments;
        return array_pop($routeSegments);
    }

//    /**
//     * @return string
//     */
//    public function getFistRouteSegment()
//    {
//        return $this->routeSegments[0];
//    }

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


}
