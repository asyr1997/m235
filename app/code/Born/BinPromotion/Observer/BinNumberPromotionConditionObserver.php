<?php
/**
 * @package   Born_BinPromotion
 * @author    Manish Bhojwani <Manish.Bhojwani@BornGroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\BinPromotion\Observer;

use Born\BinPromotion\Model\Rule\Condition\BinNumberPromotion;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class BinNumberPromotionConditionObserver
 */
class BinNumberPromotionConditionObserver implements ObserverInterface
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
            $this->getBinPromotionCondition()
        ]);

        $additional->setConditions($conditions);
        return $this;
    }

    /**
     * Get condition for bin number promotion.
     * @return array
     */
    private function getBinPromotionCondition()
    {
        return [
            'label'=> __('Bin Promotion'),
            'value'=> BinNumberPromotion::class
        ];
    }
}
