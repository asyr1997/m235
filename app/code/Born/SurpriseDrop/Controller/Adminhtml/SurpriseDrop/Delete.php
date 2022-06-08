<?php
/**
 * Born_SurpriseDrop
 *
 * PHP version 7.*
 *
 * @category  PHP
 * @package   Born_SurpriseDrop
 * @author    Born Group <support@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Controller\Adminhtml\SurpriseDrop;

use Born\SurpriseDrop\Model\SurpriseDrop;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Delete
 * Born\SurpriseDrop\Controller\Adminhtml\SurpriseDrop
 */
class Delete extends Action
{
    public const ADMIN_RESOURCE = 'Born_SurpriseDrop::born_surprisedrop';
    /**
     * @var SurpriseDrop
     */
    protected $items;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param Context $context
     * @param SurpriseDrop $items
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        Context $context,
        SurpriseDrop $items,
        ResourceConnection $resourceConnection
    ) {
        parent::__construct($context);
        $this->items = $items;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Delete Action
     *
     * @return Redirect
     *
     * @return ResponseInterface
     * @return ResultInterface
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                // init model and delete
                $items = $this->items->load($id);
                $items->delete();
                $table = $this->resourceConnection->getTableName('born_surprise_drop_products');
                $this->resourceConnection->getConnection()->delete($table, ["surprise_drop_id = $id"]);

                // display success message
                $this->messageManager->addSuccessMessage(__('The Record has been deleted.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }

        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Record to delete.'));

        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
