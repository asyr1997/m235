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
use Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitFactory;
use Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitProductsFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Born\PurchaseQuantityLimit\Helper\Data as Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * Born\PurchaseQuantityLimit\Controller\Adminhtml\PurchaseQuantityLimit
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Born_PurchaseQuantityLimit::purchasequantity_limit';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var PurchaseQuantityLimitFactory
     */
    private $purchaseQuantityLimitFactory;

    /**
     * @var PurchaseQuantityLimitProductsFactory
     */
    private $purchaseQuantityLimitProductsFactory;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param PurchaseQuantityLimitFactory $purchaseQuantityLimitFactory
     * @param PurchaseQuantityLimitProductsFactory $purchaseQuantityLimitProductsFactory
     * @param Helper $helper
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        Action\Context              $context,
        DataPersistorInterface      $dataPersistor,
        PurchaseQuantityLimitFactory         $purchaseQuantityLimitFactory,
        PurchaseQuantityLimitProductsFactory $purchaseQuantityLimitProductsFactory,
        Helper                      $helper,
        ResourceConnection          $resourceConnection
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->purchaseQuantityLimitFactory = $purchaseQuantityLimitFactory;
        $this->purchaseQuantityLimitProductsFactory = $purchaseQuantityLimitProductsFactory;
        $this->helper = $helper;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $store = $this->helper->getStores($data['store']);
            $data['store'] = $store;
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = true;
            }
            if (empty($data['id'])) {
                $data['id'] = null;
            }

            $model = $this->purchaseQuantityLimitFactory->create();
            $id = $this->getRequest()->getParam('id');

            if ($id) {
                try {
                    $model->load($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This Record no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $model->save();

                $products = explode('&', $data['products']);
                $purchaseQuantityLimitId = $model->getId();
                $table = $this->resourceConnection->getTableName('born_quantity_limitor_rules_products');
                $this->resourceConnection->getConnection()->delete($table, ["rule_id = $purchaseQuantityLimitId"]);
                $restrictedProducts = [];
                foreach ($products as $product) {
                    $PurchaseQuantityLimitId = $this->helper->checkIfProductAssignedToPurchaseQuantityLimit($product);
                    if ($PurchaseQuantityLimitId['success']) {
                        $restrictedProducts[] = $product;
                    } else {
                        $modelProduct = $this->purchaseQuantityLimitProductsFactory->create();
                        $modelProduct->setData('rule_id', $purchaseQuantityLimitId);
                        $modelProduct->setData('product_id', $product);
                        $modelProduct->save();
                    }
                }

                if (count($restrictedProducts)) {
                    $this->messageManager->addErrorMessage(
                        __(
                            '%1 products already assigned to another Purchase Quantity Limit event.',
                            implode(',', $restrictedProducts)
                        )
                    );
                }

                $this->messageManager->addSuccessMessage(__('You saved the Purchase Quantity Limit event.'));
                $this->dataPersistor->clear('born_purchasequantitylimit');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Record.'));
            }

            $this->dataPersistor->set('born_purchasequantitylimit', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
