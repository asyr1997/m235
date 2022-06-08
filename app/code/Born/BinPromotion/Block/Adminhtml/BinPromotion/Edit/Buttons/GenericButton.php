<?php
/**
 * @category  Born_BinPromotion
 * @author    Kavya Perudi <kavya.perudi@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 */
namespace Born\BinPromotion\Block\Adminhtml\BinPromotion\Edit\Buttons;

use Magento\Backend\Block\Widget\Context;
use Born\BinPromotion\Model\BinPromotionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var BinPromotionFactory
     */
    protected $binpromotionFactory;

    /**
     * @param Context $context
     * @param BinPromotionFactory $binpromotionFactory
     */
    public function __construct(
        Context $context,
        BinPromotionFactory $binpromotionFactory
    ) {
        $this->context = $context;
        $this->binpromotionFactory = $binpromotionFactory;
    }

    public function getItemId()
    {
        try {
            return $this->binpromotionFactory->create()->load(
                $this->context->getRequest()->getParam('entity_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
