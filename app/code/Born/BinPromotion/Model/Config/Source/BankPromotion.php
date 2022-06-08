<?php
/**
 * @package   Born_BinPromotion
 * @author    Manish Bhojwani <Manish.Bhojwani@BornGroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\BinPromotion\Model\Config\Source;

use Born\BinPromotion\Model\BinPromotionFactory;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class BankPromotion
 */
class BankPromotion implements ArrayInterface
{
    /**
     * @var BinPromotionFactory
     */
    private $binPromotionFactory;

    /**
     * @param BinPromotionFactory $binPromotionFactory
     */
    public function __construct(
        BinPromotionFactory $binPromotionFactory
    ) {
        $this->binPromotionFactory = $binPromotionFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $binPromotionCollection = $this->binPromotionFactory->create();
        $binPromotionCollection = $binPromotionCollection->getCollection();
        $binPromotionArr = [];
        foreach ($binPromotionCollection as $binPromotion)
        {
            $binPromotionArr[] = [
                'value' => $binPromotion->getBinNumber(),
                'label' => $binPromotion->getBankName()
            ];
        }
        return $binPromotionArr;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $binPromotionCollection = $this->binPromotionFactory->create();
        $binPromotionCollection = $binPromotionCollection->getCollection();
        $binPromotionArr = [];
        foreach ($binPromotionCollection as $binPromotion) {
            $binPromotionArr[$binPromotion->getBankName()] = $binPromotion->getBinNumber();
        }

        return $binPromotionArr;
    }
}
