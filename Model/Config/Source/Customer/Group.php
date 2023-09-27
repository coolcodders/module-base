<?php

namespace CoolCodders\Base\Model\Config\Source\Customer;

use Magento\Framework\Option\ArrayInterface;
use Magento\Customer\Model\ResourceModel\Group\Collection;

class Group implements ArrayInterface
{
    /**
     * Customer Group
     *
     * @var Collection
     */
    protected $_groupCollection;

    /**
     * Options array
     *
     * @var array
     */
    protected $_options;

    /**
     * @param Collection $groupCollection
     */

    public function __construct(Collection $groupCollection)
    {
        $this->_groupCollection = $groupCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_groupCollection->loadData()->toOptionArray(false);
    }
}
