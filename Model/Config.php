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
 * php version ^7.0
 *
 * @category Cool Cooders
 * @package  CoolCodders_Base
 * @author   Raju S <rajus@coolcodders.com>
 * @license  OSL 3.0
 * @link     https://www.coolcodders.com/
 */

namespace CoolCodders\Base\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Config
{
    /**
     * @var string
     */
    const WEBSITE_URL = 'https://www.coolcodders.com';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var TimezonInterface
     */
    private $date;

    /**
     * Config Constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $encryptor
     * @param TimezonInterface $date
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor,
        TimezoneInterface $date
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
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
