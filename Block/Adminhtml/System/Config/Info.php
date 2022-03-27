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
use Magento\Framework\View\Element\Template;

class Info extends Template
{
    /**
     * @var \CoolCodders\Base\Helper\Data
     */
    protected $helper;

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
     * @param \CoolCodders\Base\Model\ExtensionList $extensionList
     * @param \Magento\Framework\Component\ComponentRegistrarInterface $componentRegistrar
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \CoolCodders\Base\Helper\Data $helper
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \CoolCodders\Base\Model\ExtensionList $extensionList,
        \CoolCodders\Base\Helper\Data $helper,
        DataObjectFactory $dataObjectFactory,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->extensionList     = $extensionList;
        $this->helper            = $helper;
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