<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Plugin\Sidebar;

use Born\SurpriseDrop\Helper\Data as Helper;
use Magento\Checkout\Controller\Sidebar\UpdateItemQty as CoreUpdateItemQty;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Sidebar;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Json\Helper\Data as CoreData;
use Magento\Quote\Api\Data\CartItemInterface;

/**
 * Class UpdateItemQty
 * Born\SurpriseDrop\Plugin\Sidebar
 */
class UpdateItemQty
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var CoreData
     */
    private $jsonHelper;

    /**
     * @var Sidebar
     */
    private $sidebar;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param Cart $cart
     * @param CoreData $jsonHelper
     * @param Sidebar $sidebar
     * @param Helper $helper
     */
    public function __construct(
        Cart $cart,
        CoreData $jsonHelper,
        Sidebar $sidebar,
        Helper $helper
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->sidebar = $sidebar;
        $this->cart = $cart;
        $this->helper = $helper;
    }

    public function aroundExecute(CoreUpdateItemQty $subject, \Closure $proceed)
    {
        if ($this->helper->isEnabled()) {
            $itemId = (int)$subject->getRequest()->getParam('item_id');
            $itemQty = $subject->getRequest()->getParam('item_qty') * 1;

            $item = $this->cart->getQuote()->getItemById($itemId);
            if (!$item instanceof CartItemInterface) {
                $errorMsg = __("The quote item isn't found. Verify the item and try again.");
                return $this->jsonResponse($subject, $errorMsg);
            }

            $productId = $item->getProduct()->getId();
            $response = $this->helper->checkIfProductAssignedToSurpriseDrop($productId);
            if ($response['success'] && $itemQty > 1) {
                $this->sidebar->checkQuoteItem($itemId);
                $this->sidebar->updateQuoteItem($itemId, 1);
                return $this->jsonResponse($subject, $this->helper->getQuantityLimitMessage());
            }
        }

        return $proceed();
    }

    /**
     * Compile JSON response
     *
     * @param $subject
     * @param string $error
     * @return Http
     */
    protected function jsonResponse($subject, $error = '')
    {
        return $subject->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($this->sidebar->getResponseData($error))
        );
    }
}
