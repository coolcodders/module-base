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
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Config
{
    const COMMUNITY = "Community";
    const ENTERPRISE = "Enterprise";

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
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * Config Constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param EncryptorInterface $encryptor
     * @param TimezonInterface $date
     * @param ProductMetadataInterface $productMetadata
     * @param Json $jsonSerializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor,
        TimezoneInterface $date,
        ProductMetadataInterface $productMetadata,
        Json $jsonSerializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
        $this->date = $date;
        $this->productMetadata = $productMetadata;
        $this->jsonSerializer = $jsonSerializer;
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

    /**
     * array to json
     * 
     * @param mixed $array
     * 
     * @return string
     */
    public function serialize($array)
    {
        return $this->jsonSerializer->serialize($array);
    }

    /**
     * array to json
     * 
     * @param string $jsonString
     * 
     * @return mixed
     */
    public function unserialize($jsonString)
    {
        return $this->jsonSerializer->unserialize($jsonString);
    }

    /**
     * Validate two array
     * 
     * @param mixed $arrayCollection
     * @param mixed $array
     * 
     * @return boolean
     */
    public function isArrayExistInCollection($arrayCollection, $array)
    {
        if(is_array($arrayCollection) && is_array($array)) {
            foreach ($arrayCollection as $arrayItem) {
                if(count(array_diff_assoc($arrayItem, $array)) == 0) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * check magento is enterprise
     * 
     * @return bool
     */
    public function isEnterprise()
    {
        if ($this->productMetadata->getEdition() == self::ENTERPRISE) {
            return true;
        }
        return false;
    }

    /**
     * check magento is community
     * 
     * @return bool
     */
    public function isCommunity()
    {
        if ($this->productMetadata->getEdition() == self::COMMUNITY) {
            return true;
        }
        return false;
    }
}
