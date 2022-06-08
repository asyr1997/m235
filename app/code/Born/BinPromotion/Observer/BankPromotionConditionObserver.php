<?php
/**
 * @package   Born_BinPromotion
 * @author    Manish Bhojwani <Manish.Bhojwani@BornGroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\BinPromotion\Observer;

use Born\BinPromotion\Model\Rule\Condition\BankPromotion;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class BankPromotionConditionObserver
 */
class BankPromotionConditionObserver implements ObserverInterface
{
    /**
     * Execute observer.
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $additional = $observer->getAdditional();
        $conditions = (array) $additional->getConditions();

        $conditions = array_merge_recursive($conditions, [
            $this->getBankPromotionCondition()
        ]);

        $additional->setConditions($conditions);
        return $this;
    }

    /**
     * Get condition of bank promotion.
     * @return array
     */
    private function getBankPromotionCondition()
    {
        return [
            'label'=> __('Bank Promotion'),
            'value'=> BankPromotion::class
        ];
    }
}
