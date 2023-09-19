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

namespace CoolCodders\Base\Block;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Pricing\Render;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\RendererList;
use Magento\Framework\Url\Helper\Data;
use Magento\Catalog\Block\Product\Context as CatalogContext;
use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ProductList\Item\AddTo\Compare;
use Magento\Catalog\Block\Product\ProductList\Item\Container;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Catalog\ViewModel\Product\OptionsData;
use Magento\Swatches\Block\Product\Renderer\Listing\Configurable;
use Magento\Swatches\ViewModel\Product\Renderer\Configurable as ConfigurableViewModel;
use Magento\Wishlist\Block\Catalog\Product\ProductList\Item\AddTo\Wishlist;

class AbstractProduct extends Template
{
    private $_wishlistTemplate = 'Magento_Wishlist::catalog/product/list/addto/wishlist.phtml';
    private $_compareTemplate = 'Magento_Catalog::product/list/addto/compare.phtml';
    private $_swatchTemplate = 'Magento_Swatches::product/listing/renderer.phtml';

    /**
     * @var Data
     */
    protected $urlHelper;
    
    /**
     * @var CatalogContext
     */
    protected $catalogContext;

    /**
     * @var NewProductsCollection
     */
    protected $newProductCollection;

    /**
     * @param Context $context
     * @param CatalogContext $catalogContext
     * @param Data $urlHelper
     * @param OptionsData $optionsData
     * @param ConfigurableViewModel $configrableRandrer
     * @param array $data
     */
    public function __construct(
        Context $context,
        CatalogContext $catalogContext,
        Data $urlHelper,
        OptionsData $optionsData,
        ConfigurableViewModel $configrableRandrer,
        array $data = []
    ) {
        if(isset($data['enable_addtocart']) && $data['enable_addtocart']) {
            $data["viewModel"] = $optionsData;
        } else {
            $data['enable_addtocart'] = false;
        }
        if(isset($data['enable_swatch']) && $data['enable_swatch']) {
            $data["configrableRandrer"] = $configrableRandrer;
        } else {
            $data['enable_swatch'] = false;
        }
        $this->catalogContext = $catalogContext;
        $this->urlHelper = $urlHelper;
        parent::__construct($context, $data);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if($this->getData('enable_compare') || $this->getData('enable_wishlist')) {
            $addToBlock = $this->addChild('addto', Container::class);
            if($this->getData('enable_wishlist')) {
                $wishlistBlock = $addToBlock->addChild(
                    'wishlist',
                    Wishlist::class,
                    [
                        'template' => $this->_wishlistTemplate
                    ]
                );
            }
            if($this->getData('enable_compare')) {
                $compareBlock = $addToBlock->addChild(
                    'compare',
                    Compare::class,
                    [
                        'template' => $this->_compareTemplate
                    ]
                );
            }
        }
        $renderersBlock = $this->addChild('details.renderers', RendererList::class);
        $renderersBlock->addChild('default', Template::class);
        if($this->getData('enable_swatch')) {
            $renderersBlock->addChild(
                'configurable',
                Configurable::class,
                [
                    'template' => $this->_swatchTemplate
                ]
            )->setData('configurable_view_model', $this->getData('configrableRandrer'));
        }
        return $this;
    }

    public function getProductCollection()
    {

    }

    /**
     * Retrieve Product URL using UrlDataObject
     *
     * @param Product $product
     * @param array $additional the route params
     * @return string
     */
    public function getProductUrl($product, $additional = [])
    {
        if ($this->hasProductUrl($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            return $product->getUrlModel()->getUrl($product, $additional);
        }

        return '#';
    }

    /**
     * Check Product has URL
     *
     * @param Product $product
     * @return bool
     */
    public function hasProductUrl($product)
    {
        $productVisibilities = $product->getVisibleInSiteVisibilities();
        $productHasUrl = $product->hasUrlDataObject();
        if ($productVisibilities) {
            return true;
        }
        if ($productHasUrl) {
            if (in_array($productHasUrl->getVisibility(), $productVisibilities)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get product reviews summary
     *
     * @param Product $product
     * @param bool $templateType
     * @param bool $displayIfNoReviews
     * @return string
     */
    public function getReviewsSummaryHtml(
        Product $product,
        $templateType = false,
        $displayIfNoReviews = false
    ) {
        return $this->catalogContext
            ->getReviewRenderer()
            ->getReviewsSummaryHtml($product, $templateType, $displayIfNoReviews);
    }

    /**
     * Retrieve product details html
     *
     * @param Product $product
     * @return mixed
     */
    public function getProductDetailsHtml(Product $product)
    {
        $renderer = $this->getDetailsRenderer($product->getTypeId());
        if ($renderer) {
            $renderer->setProduct($product);
            return $renderer->toHtml();
        }
        return '';
    }

    /**
     * Get the renderer that will be used to render the details block
     *
     * @param string | null $type
     * @return bool | AbstractBlock
     */
    public function getDetailsRenderer($type = null)
    {
        if ($type === null) {
            $type = 'default';
        }
        $rendererList = $this->getDetailsRendererList();
        if ($rendererList) {
            return $rendererList->getRenderer($type, 'default');
        }
        return null;
    }

    /**
     * Return the list of details
     *
     * @return RendererList
     */
    protected function getDetailsRendererList()
    {
        return $this->getDetailsRendererListName() ? $this->getLayout()->getBlock(
            $this->getDetailsRendererListName()
        ) : $this->getChildBlock(
            'details.renderers'
        );
    }

    public function getAddToCartPostParams(Product $product)
    {
        $url = "";
        $additional = ['_escape' => false];
        if (!$product->getTypeInstance()->isPossibleBuyFromList($product)) {
            if (!isset($additional['_escape'])) {
                $additional['_escape'] = true;
            }
            if (!isset($additional['_query'])) {
                $additional['_query'] = [];
            }
            $additional['_query']['options'] = 'cart';

            $url = $this->getProductUrl($product, $additional);
        }
        $url = $this->catalogContext->getCartHelper()->getAddUrl($product, $additional);
        return [
            'action' => $url,
            'data' => [
                'product' => (int) $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * Return HTML block with price
     *
     * @param Product $product
     * @return string
     */
    public function getProductPrice(Product $product)
    {
        return $this->getProductPriceHtml(
            $product,
            FinalPrice::PRICE_CODE,
            Render::ZONE_ITEM_LIST
        );
    }

    /**
     * Return HTML block with tier price
     *
     * @param Product $product
     * @param string $priceType
     * @param string $renderZone
     * @param array $arguments
     * @return string
     */
    public function getProductPriceHtml(
        Product $product,
        $priceType,
        $renderZone = Render::ZONE_ITEM_LIST,
        array $arguments = []
    ) {
        if (!isset($arguments['zone'])) {
            $arguments['zone'] = $renderZone;
        }

        /** @var Render $priceRender */
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        $price = '';

        if ($priceRender) {
            $price = $priceRender->render($priceType, $product, $arguments);
        }
        return $price;
    }

    /**
     * Retrieve product image
     *
     * @param Product $product
     * @param string $imageId
     * @param array $attributes
     * @return Image
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->catalogContext->getImageBuilder()->create($product, $imageId, $attributes);
    }
}
