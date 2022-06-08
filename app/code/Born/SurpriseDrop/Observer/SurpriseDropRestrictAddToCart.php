<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Observer;

use Born\SurpriseDrop\Helper\Data as Helper;
use Magento\Checkout\Model\SessionFactory as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Class SurpriseDropRestrictAddToCart
 * Born\SurpriseDrop\Observer
 */
class SurpriseDropRestrictAddToCart implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param ManagerInterface $messageManager
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param Helper $helper
     */
    public function __construct(
        ManagerInterface $messageManager,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        Helper $helper
    ) {
        $this->messageManager = $messageManager;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->helper = $helper;
    }

    /**
     * add to cart event handler.
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if (!$this->helper->isEnabled()) {
            return $this;
        }

        $quote = $this->checkoutSession->create()->getQuote();
        $productId = $observer->getRequest()->getParam('product');
        $qty = $observer->getRequest()->getParam('qty');
        $warningFlag = false;
        $warningMessage = "";

        $checkSurpriseDrop = $this->helper->checkIfProductAssignedToSurpriseDrop($productId);
        if ($checkSurpriseDrop['success']) {
            if ($this->customerSession->isLoggedIn()) {
                $cartItems = [];
                foreach ($quote->getAllVisibleItems() as $item) {
                    $cartItems[] = $item->getProduct()->getId();
                }
                if ($this->helper->checkIfCustomerOrderedSurpriseDrop(
                    $this->customerSession->getCustomer()->getId(),
                    $checkSurpriseDrop['surprise_drop_id'])) {
                    $warningMessage = $this->helper->getAlreadyPurchasedMessage();
                    $warningFlag = true;
                } else if ($quote->hasProductId($productId) ||
                    count(array_intersect($checkSurpriseDrop['products'], $cartItems))) {
                    $warningMessage = $this->helper->getAlreadyAddedMessage();
                    $warningFlag = true;
                } else if ($qty > 1) {
                    $warningMessage = $this->helper->getQuantityLimitMessage();
                    $warningFlag = true;
                }
            } else {
                $warningMessage = $this->helper->getCustomerLoginMessage();
                $warningFlag = true;
            }

            if ($warningFlag) {
                $this->messageManager->addErrorMessage($warningMessage);
                $observer->getRequest()->setParam('product', false);
            }
        }

        return $this;
    }
}
