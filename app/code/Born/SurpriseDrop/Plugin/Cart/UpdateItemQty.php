<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Plugin\Cart;

use Born\SurpriseDrop\Helper\Data as Helper;
use Magento\Checkout\Controller\Cart\UpdateItemQty as CoreUpdateItemQty;
use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote\Item;

/**
 * Class UpdateItemQty
 * Born\SurpriseDrop\Plugin\Cart
 */
class UpdateItemQty
{
    /**
     * @var RequestQuantityProcessor
     */
    private $quantityProcessor;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param RequestQuantityProcessor $quantityProcessor
     * @param CheckoutSession $checkoutSession
     * @param Json $json
     * @param Helper $helper
     */
    public function __construct(
        RequestQuantityProcessor $quantityProcessor,
        CheckoutSession $checkoutSession,
        Json $json,
        Helper $helper
    ) {
        $this->quantityProcessor = $quantityProcessor;
        $this->checkoutSession = $checkoutSession;
        $this->json = $json;
        $this->helper = $helper;
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function aroundExecute(CoreUpdateItemQty $subject, \Closure $proceed)
    {
        if ($this->helper->isEnabled()) {
            $cartData = $subject->getRequest()->getParam('cart');

            $this->validateCartData($cartData);

            $cartData = $this->quantityProcessor->process($cartData);
            $quote = $this->checkoutSession->getQuote();

            foreach ($cartData as $itemId => $itemInfo) {
                $item = $quote->getItemById($itemId);
                $qty = isset($itemInfo['qty']) ? (double) $itemInfo['qty'] : 0;
                $productId = $item->getProduct()->getId();
                $response = $this->helper->checkIfProductAssignedToSurpriseDrop($productId);
                if ($response['success'] && $qty > 1) {
                    $this->updateItemQuantity($item, 1);
                    return $subject->getResponse()->representJson(
                        $this->json->serialize($this->getResponseData($this->helper->getQuantityLimitMessage()))
                    );
                }
            }
        }

        return $proceed();
    }

    /**
     * Validates cart data
     *
     * @param array|null $cartData
     * @return void
     * @throws LocalizedException
     */
    private function validateCartData($cartData = null)
    {
        if (!is_array($cartData)) {
            throw new LocalizedException(
                __('Something went wrong while saving the page. Please refresh the page and try again.')
            );
        }
    }

    /**
     * Returns response data.
     *
     * @param string $error
     * @return array
     */
    private function getResponseData(string $error = ''): array
    {
        $response = ['success' => true];

        if (!empty($error)) {
            $response = [
                'success' => false,
                'error_message' => $error,
            ];
        }

        return $response;
    }

    /**
     * Updates quote item quantity.
     *
     * @param Item $item
     * @param float $qty
     * @return void
     * @throws LocalizedException
     */
    private function updateItemQuantity(Item $item, float $qty)
    {
        if ($qty > 0) {
            $item->clearMessage();
            $item->setQty($qty);

            if ($item->getHasError()) {
                throw new LocalizedException(__($item->getMessage()));
            }
        }
    }
}
