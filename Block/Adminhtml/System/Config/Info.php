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

use Magento\Backend\Block\Context;
use Magento\Config\Block\System\Config\Form\Field\Heading;
use Magento\Framework\Data\Form\Element\AbstractElement;
use CoolCodders\Base\Model\Config;
use CoolCodders\Base\Model\ExtensionList;

class Info extends Heading
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
     * Extensions constructor.
     *
     * @param ExtensionList $extensionList
     * @param Config $config
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        ExtensionList $extensionList,
        Config $config,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->extensionList     = $extensionList;
        $this->config            = $config;
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

    public function getInstalledExtension()
    {
        return $this->extensionList->getInstalledExtensionList();
    }

    /**
     * Render element html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $label = $element->getLabel();
        $info = __("CoolCodders Installed Extension List");

        $html = "<div class='cool-module-info'>";
        $html .= "<div class='container'>";
        $html .= "<h2>About CoolCodders</h2>";
        $html .= "<p>".__('Welcome to CoolCodders. We provide magento extension and magento services.')."</p>";
        $html .= "<p style='margin:10px 0px'> We are ecommerce (Magento) expert with more then 8 years of experience. We have adobe certified developers. Experience in migration application from other platfrom to Magento, Developed custom requirment in Magento, Third party ERP intergration with the Magento application, Custom design as per the client needs and many more we done.</p>";
        $html .= "<p style='margin:10px 0px'> Fill free to reach out to out this email <a href='mailto: info@coolcodders.com'>info@coolcodders.com</a> if you face any issue with our extension or customize extension.</p>.";
        $html .= "<p>". __("Website:")." <a href='https://www.coolcodders.com' target='_blank'>". __("www.coolcoders.com"). "</a></p>";
        $html .= "<p style='margin:0px 0px 30px 0px'>". __("Email:") ." <a href='mailto:info@coolcodders.com'>" .__("info@coolcoders.com") ."</a>.</p>";
        $html .= "<h2 class='heading'>".$info."</h2>";
        $html .= "<table class='data-grid'><thead><tr><th class='data-grid-th' width='250'>Name</th><th class='data-grid-th' width='150'>Version</th><th class='data-grid-th'>Description</th></tr></thead>";

        foreach ($this->getInstalledExtension() as $key => $value) {
            $html .= "<tr><td>".$value["name"]."</td><td>Version: ".$value["version"]."</td><td>".$value["description"]."</td></tr>";
        }
        $html .= "</table>";
        $html .= "</div>";
        $html .= "</div>";
        return $html;
    }
}