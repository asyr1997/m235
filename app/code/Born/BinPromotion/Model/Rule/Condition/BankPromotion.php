<?php
/**
 * @package   Born_BinPromotion
 * @author    Manish Bhojwani <Manish.Bhojwani@BornGroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\BinPromotion\Model\Rule\Condition;

use Born\BinPromotion\Model\Config\Source\BankPromotion as SourceBankPromotion;
use Magento\Framework\Model\AbstractModel;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class BankPromotion
 */
class BankPromotion extends AbstractCondition
{
    /**
     * @var SourceBankPromotion
     */
    private $sourceBankPromotion;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * Constructor
     * @param Context $context
     * @param SourceBankPromotion $sourceBankPromotion
     * @param CheckoutSession $checkoutSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        SourceBankPromotion $sourceBankPromotion,
        CheckoutSession $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->sourceBankPromotion = $sourceBankPromotion;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Load attribute options
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption([
            'bank_promotion' => __('Bank Promotion')
        ]);
        return $this;
    }

    /**
     * Get input type
     * @return string
     */
    public function getInputType()
    {
        return 'select';
    }

    /**
     * Get value element type
     * @return string
     */
    public function getValueElementType()
    {
        return 'select';
    }

    /**
     * Get value select options
     * @return array|mixed
     */
    public function getValueSelectOptions()
    {
        if (!$this->hasData('value_select_options')) {
            $this->setData(
                'value_select_options',
                $this->sourceBankPromotion->toOptionArray()
            );
        }
        return $this->getData('value_select_options');
    }

    /**
     * Validate Bank Information Rule Condition
     * @param AbstractModel $model
     * @return bool
     */
    public function validate(AbstractModel $model)
    {
        $binNumber = $this->checkoutSession->getBinNumber();
        $model->setData('bank_promotion', $binNumber);

        return parent::validate($model);
    }
}
