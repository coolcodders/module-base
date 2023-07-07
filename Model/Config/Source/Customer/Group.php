<?php

namespace CoolCodders\Base\Model\Config\Source\Customer;

use Magento\Framework\Option\ArrayInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

class Group implements ArrayInterface
{
    protected $options;

    public function __construct(CollectionFactory $customerGroupFactory)
    {
        $this->customerGroupFactory = $customerGroupFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if (!$this->options) {
            $this->options = $this->customerGroupFactory->create()->loadData()->toOptionArray();
        }
        return $this->options;
    }
}
