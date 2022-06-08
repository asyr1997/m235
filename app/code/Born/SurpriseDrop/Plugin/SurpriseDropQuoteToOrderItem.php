<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Plugin;

use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Quote\Model\Quote\Item\ToOrderItem;
use Magento\Sales\Model\Order\Item;

/**
 * Class SurpriseDropQuoteToOrderItem
 * Born\SurpriseDrop\Plugin
 */
class SurpriseDropQuoteToOrderItem
{
    /**
     * @param ToOrderItem $subject
     * @param \Closure $proceed
     * @param AbstractItem $item
     * @param $additional
     * @return Item
     */
    public function aroundConvert(
        ToOrderItem $subject,
        \Closure $proceed,
        AbstractItem $item,
        $additional = []
    ) {
        /** @var $orderItem Item */
        $orderItem = $proceed($item, $additional);
        $orderItem->setSurpriseDropId($item->getSurpriseDropId());
        return $orderItem;
    }
}
