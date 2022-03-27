<?php
/**
 * Cool Codders
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * php version 7.0
 *
 * @category Cool Cooders
 * @package  CoolCodders_Base
 * @author   Raju S <rajus@coolcodders.com>
 * @license  OSL 3.0
 * @link     https://www.coolcodders.com/
 */

namespace CoolCodders\Base\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;


class Data extends AbstractHelper
{
    /**
     * @var string
     */
    const WEBSITE_URL = 'https://www.coolcodders.com';

    protected $date;

    /**
     * Data constructor.
     *
     * @param Context $context
     */
    public function __construct(
        Context $context,
        TimezoneInterface $date
    ) {
        parent::__construct($context);
        $this->date = $date;
    }

    /**
     * Get date as per timezon
     * 
     * @param string $dateFormate
     * 
     * @return string
     */
    public function getDate($dateFormat)
    {
        return $this->date->date()->formate($dateFormat);
    }

    /**
     * Get Config value
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORES
        );
    }
}
