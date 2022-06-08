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

/**
 * controller to Edit record from admin.
 * Class Edit
 * Born\PurchaseQuantityLimit\Controller\Adminhtml\PurchaseQuantityLimit
 */
class Edit extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Born_PurchaseQuantityLimit::born_purchasequantitylimit';

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitFactory $purchasequantitylimitFatory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitFactory $purchasequantitylimitFatory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->purchasequantitylimitFatory = $purchasequantitylimitFatory;
        parent::__construct($context);
    }

    /**
     * Init Action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initAction()
    {

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Born_PurchaseQuantityLimit::born_purchasequantitylimit');
        return $resultPage;
    }

    /**
     * Edit
     *
     * @return \Magento\Backend\Model\View\Result\Page
     * @return \Magento\Framework\App\ResponseInterface
     * @return \Magento\Framework\Controller\Result\Redirect
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->purchasequantitylimitFatory->create();

        // 2. Initial checking
        if ($id) {

            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This item no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('born_purchasequantitylimit', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit') : __('New'),
            $id ? __('Edit') : __('New')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Purchase Quantity Limit'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __(''));

        return $resultPage;
    }
}
