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

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Born\PurchaseQuantityLimit\Model\ResourceModel\PurchaseQuantityLimit\CollectionFactory;

/**
 * controller to MassDelete record(s) from admin.
 * Class MassDelete
 * Born\PurchaseQuantityLimit\Controller\Adminhtml\PurchaseQuantityLimit
 */
class MassDelete extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Born_PurchaseQuantityLimit::born_purchasequantitylimit';

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Mass Delete
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();

        foreach ($collection as $item) {
            $item->delete();
        }

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been deleted.', $collectionSize)
        );

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
