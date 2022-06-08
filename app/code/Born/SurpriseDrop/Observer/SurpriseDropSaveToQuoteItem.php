<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Born\SurpriseDrop\Helper\Data as Helper;

/**
 * Class SurpriseDropSaveToQuoteItem
 * Born\SurpriseDrop\Observer
 */
class SurpriseDropSaveToQuoteItem implements ObserverInterface
{
    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param Helper $helper
     */
    public function __construct(
        Helper $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param Observer $observer
     * @return $this;
     * @throws Exception
     */
    public function execute(Observer $observer)
    {
        if ($this->helper->isEnabled()) {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $observer->getEvent()->getProduct();

            /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
            $quoteItem = $observer->getEvent()->getQuoteItem();

            $response = $this->helper->checkIfProductAssignedToSurpriseDrop($product->getId());
            if ($response['success']) {
                $quoteItem->setSurpriseDropId($response['surprise_drop_id']);
            }
        }

        return $this;
    }
}
