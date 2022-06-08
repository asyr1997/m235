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

/**
 * controller to Delete record from admin.
 * Class Delete
 * Born\PurchaseQuantityLimit\Controller\Adminhtml\PurchaseQuantityLimit
 */
class Delete extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Born_PurchaseQuantityLimit::born_purchasequantitylimit';
    /**
     * @var \Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit
     */
    protected $items;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit $items
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit $items
    ) {
        parent::__construct($context);
        $this->items = $items;
    }

    /**
     * Delete Action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @return \Magento\Framework\App\ResponseInterface
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                // init model and delete
                $items = $this->items->load($id);

                $items->delete();

                // display success message
                $this->messageManager->addSuccessMessage(__('The item has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a item to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
