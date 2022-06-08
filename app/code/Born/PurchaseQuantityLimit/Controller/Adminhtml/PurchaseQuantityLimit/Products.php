<?php
/**
 * Born_PurchaseQuantityLimit
 *
 * PHP version 7.*
 *
 * @category  PHP
 * @package   Born_PurchaseQuantityLimit
 * @author    Born Group <support@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */

namespace Born\PurchaseQuantityLimit\Controller\Adminhtml\PurchaseQuantityLimit;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\TestFramework\ErrorLog\Logger;

/**
 * Class Products
 * Born\PurchaseQuantityLimit\Controller\Adminhtml\PurchaseQuantityLimit
 */
class Products extends Action
{
    /**
     * @var LayoutFactory
     */
    private $resultLayoutFactory;

    /**
     * Products constructor.
     * @param Action\Context $context
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    public function _isAllowed()
    {

        return $this->_authorization->isAllowed('Born_PurchaseQuantityLimit::manage');
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {

        $resultLayout = $this->resultLayoutFactory->create();

        $resultLayout->getLayout()->getBlock('purchasequantitylimit.edit.tab.products')
            ->setInProducts($this->getRequest()->getPost('purchasequantitylimit_products', null));

        return $resultLayout;
    }
}
