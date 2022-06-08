<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Plugin\SalesRule\Model;

use Born\SurpriseDrop\Helper\Data as Helper;
use Magento\SalesRule\Model\ResourceModel\Rule\Collection;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;

/**
 * Class RulesApplier
 * Born\SurpriseDrop\Plugin\SalesRule\Model
 */
class RulesApplier
{
    /**
     * @var Collection
     */
    private $ruleCollection;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param CollectionFactory $rulesFactory
     * @param Helper $helper
     */
    public function __construct(
        CollectionFactory $rulesFactory,
        Helper $helper
    ) {
        $this->ruleCollection = $rulesFactory;
        $this->helper = $helper;
    }

    public function aroundApplyRules(
        \Magento\SalesRule\Model\RulesApplier $subject,
        \Closure $proceed,
        $item,
        $rules,
        $skipValidation,
        $couponCode
    ) {
        $response = $this->helper->checkIfProductAssignedToSurpriseDrop($item->getProductId());
        if ($response['success']) {
            $rules = $this->ruleCollection->create()
                ->addFieldToFilter("rule_id", ["eq" => ""]);
        }

        return $proceed($item, $rules, $skipValidation, $couponCode);
    }
}
