<?php
/**
 * @package   Born_BinPromotion
 * @author    Manish Bhojwani <Manish.Bhojwani@BornGroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\BinPromotion\Controller\Checkout;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

class ValidateBinPromotion extends Action
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @param Context $context
     * @param Cart $cart
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        Context $context,
        Cart $cart,
        CheckoutSession $checkoutSession
    ) {
        $this->cart = $cart;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|void
     * @throws \Exception
     */
    public function execute()
    {
        $ccNumber = $this->getRequest()->getParam('cc_number');
        $pMethod = $this->getRequest()->getParam('payment_method');
        $binNumber = substr($ccNumber, 0, 6);
        $this->checkoutSession->setBinNumber($binNumber);
        $quote = $this->cart->getQuote();
        $quote->getPayment()->setMethod($pMethod);
        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();
        $quote->save();
    }
}
