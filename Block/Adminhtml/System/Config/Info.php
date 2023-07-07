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
 *
 * @category Cool Codders Extension
 * @package  CoolCodders_Base
 * @author   Raju S <rajus@coolcodders.com>
 * @license  OSL 3.0
 * @link     http://www.coolcodders.com
 */
namespace CoolCodders\Base\Block\Adminhtml\System\Config;

use Magento\Framework\DataObjectFactory;
use Magento\Framework\Component\ComponentRegistrarInterface;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\View\Element\Template;
use CoolCodders\Base\Model\Config;
use CoolCodders\Base\Model\ExtensionList;

class Info extends Template
{
    /**
     * @var \CoolCodders\Base\Model\Config
     */
    protected $config;

    /**
     * @var \CoolCodders\Base\Model\ExtensionList
     */
    protected $extensionList;

    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * @var string
     */
    protected $_template = 'CoolCodders_Base::config/info.phtml';

    /**
     * Extensions constructor.
     *
     * @param ExtensionList $extensionList
     * @param ComponentRegistrarInterface $componentRegistrar
     * @param ReadFactory $readFactory
     * @param Config $config
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ExtensionList $extensionList,
        Config $config,
        DataObjectFactory $dataObjectFactory,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->extensionList     = $extensionList;
        $this->config            = $config;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @return string
     */
    public function getStoreUrl()
    {
        return $this->helper->getStoreUrl();
    }

    /**
     * @param string $name
     * @return string
     */
    public function getExtensionVersion($name)
    {
        return $this->extensionList->getInstalledVersion($name);
    }
}