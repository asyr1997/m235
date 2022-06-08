<?php
/**
 * Born_MopRestriction
 *
 * PHP version 7.*
 *
 * @category  PHP
 * @package   Born_MopRestriction
 * @author    Born Group <support@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */

namespace Born\MopRestriction\Controller\Adminhtml\MopRestriction;

use Magento\Backend\App\Action;
use Born\MopRestriction\Model\MopRestrictionFactory;
use Born\MopRestriction\Model\MopRestrictionProductsFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Born\MopRestriction\Helper\Data as Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * Born\MopRestriction\Controller\Adminhtml\MopRestriction
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Born_MopRestriction::mop_restriction';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var MopRestrictionFactory
     */
    private $mopRestrictionFactory;

    /**
     * @var MopRestrictionProductsFactory
     */
    private $mopRestrictionProductsFactory;

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var \Magento\Payment\Helper\Data
     */
    protected $paymentHelper;
    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param MopRestrictionFactory $mopRestrictionFactory
     * @param MopRestrictionProductsFactory $mopRestrictionProductsFactory
     * @param Helper $helper
     * @param ResourceConnection $resourceConnection
     * @param \Magento\Payment\Helper\Data $paymentHelper
     */
    public function __construct(
        Action\Context              $context,
        DataPersistorInterface      $dataPersistor,
        MopRestrictionFactory         $mopRestrictionFactory,
        MopRestrictionProductsFactory $mopRestrictionProductsFactory,
        Helper                      $helper,
        \Magento\Payment\Helper\Data $paymentHelper,
        ResourceConnection          $resourceConnection
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->mopRestrictionFactory = $mopRestrictionFactory;
        $this->mopRestrictionProductsFactory = $mopRestrictionProductsFactory;
        $this->helper = $helper;
        $this->paymentHelper = $paymentHelper;
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
//            $payment = $this->paymentHelper->getPaymentMethodList($data['payment_methods']);
//            $data['payment_methods'] = $payment;

//            print_r($data);
//            exit;
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = true;
            }
            if (empty($data['id'])) {
                $data['id'] = null;
            }

            $model = $this->mopRestrictionFactory->create();
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
                $mopRestrictionId = $model->getId();
                $table = $this->resourceConnection->getTableName('born_mop_restriction_products');
                $this->resourceConnection->getConnection()->delete($table, ["mop_id = $mopRestrictionId"]);
                $restrictedProducts = [];
                foreach ($products as $product) {
                    $MopRestrictionId = $this->helper->checkIfProductAssignedToMopRestriction($product);
                    if ($MopRestrictionId['success']) {
                        $restrictedProducts[] = $product;
                    } else {
                        $modelProduct = $this->mopRestrictionProductsFactory->create();
                        $modelProduct->setData('mop_id', $mopRestrictionId);
                        $modelProduct->setData('product_id', $product);
                        $modelProduct->save();
                    }
                }

                if (count($restrictedProducts)) {
                    $this->messageManager->addErrorMessage(
                        __(
                            '%1 products already assigned to another Mop Restriction event.',
                            implode(',', $restrictedProducts)
                        )
                    );
                }

                $this->messageManager->addSuccessMessage(__('You saved the Mop Restriction event.'));
                $this->dataPersistor->clear('born_moprestriction');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?: $e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Record.'));
            }

            $this->dataPersistor->set('born_moprestriction', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
