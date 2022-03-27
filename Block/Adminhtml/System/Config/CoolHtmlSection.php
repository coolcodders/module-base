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

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\LayoutInterface;

class CoolHtmlSection extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    protected $layout;

    /**
     * Extensions constructor.
     *
     * @param \MageWorx\Info\Block\Adminhtml\Extensions $extensionBlock
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\View\Helper\Js $jsHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        LayoutInterface $layout,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->layout = $layout;
    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        return $this->layout->createBlock(Info::class)->toHtml();
        //return $this->extensionBlock->toHtml();
    }
}
