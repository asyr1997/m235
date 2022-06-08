<?php
/**
 * @package   Born_BinPromotion
 * @author    Manish Bhojwani <Manish.Bhojwani@BornGroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\BinPromotion\Model\Rule\Condition;

use Magento\Framework\Model\AbstractModel;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Class BinNumberPromotion
 */
class BinNumberPromotion extends AbstractCondition
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * Constructor
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param array $data
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Load attribute options
     * @return $this
     */
    public function loadAttributeOptions()
    {
        $this->setAttributeOption([
            'bin_promotion' => __('Bin Number Promotion')
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
        return 'text';
    }

    /**
     * Validate Bin Number Rule Condition
     * @param AbstractModel $model
     * @return bool
     */
    public function validate(AbstractModel $model)
    {
        $binNumber = $this->checkoutSession->getBinNumber();
        $model->setData('bin_promotion', $binNumber);

        return parent::validate($model);
    }
}
