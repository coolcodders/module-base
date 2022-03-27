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
namespace CoolCodders\Base\Block\Adminhtml\FormField\Renderer;

class ColorPicker extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
    * Get the after element html.
    *
    * @return mixed
    */
    public function getAfterElementHtml()
    {
        // here you can write your code.
        $customDiv = '<div style="width:600px;height:200px;margin:10px 0;border:2px solid #000" id="customdiv"><h1 style="margin-top: 12%;margin-left:40%;">Custom Div</h1></div>';
        return $customDiv;
    }
}