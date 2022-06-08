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

use Magento\Backend\App\Action;
use Born\SurpriseDrop\Model\SurpriseDropFactory;
use Born\SurpriseDrop\Model\SurpriseDropProductsFactory;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Request\DataPersistorInterface;
use Born\SurpriseDrop\Helper\Data as Helper;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * Born\SurpriseDrop\Controller\Adminhtml\SurpriseDrop
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Born_SurpriseDrop::surprise_drop';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var SurpriseDropFactory
     */
    private $surpriseDropFactory;

    /**
     * @var SurpriseDropProductsFactory
     */
    private $surpriseDropProductsFactory;

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
     * @param SurpriseDropFactory $surpriseDropFactory
     * @param SurpriseDropProductsFactory $surpriseDropProductsFactory
     * @param Helper $helper
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        SurpriseDropFactory $surpriseDropFactory,
        SurpriseDropProductsFactory $surpriseDropProductsFactory,
        Helper $helper,
        ResourceConnection $resourceConnection
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->surpriseDropFactory = $surpriseDropFactory;
        $this->surpriseDropProductsFactory = $surpriseDropProductsFactory;
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
            if (isset($data['status']) && $data['status'] === 'true') {
                $data['status'] = true;
            }
            if (empty($data['id'])) {
                $data['id'] = null;
            }

            $model = $this->surpriseDropFactory->create();
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

                if (isset($data["products"])) {
                    $products = explode("&", $data["products"]);
                    $surpriseDropId = $model->getId();
                    $table = $this->resourceConnection->getTableName('born_surprise_drop_products');
                    $this->resourceConnection->getConnection()->delete($table, ["surprise_drop_id = $surpriseDropId"]);
                    $restrictedProducts = [];
                    foreach ($products as $product) {
                        $checkSurpriseDrop = $this->helper->checkIfProductAssignedToSurpriseDrop($product);
                        if ($checkSurpriseDrop['success']) {
                            $restrictedProducts[] = $product;
                        } else{
                            $modelProduct = $this->surpriseDropProductsFactory->create();
                            $modelProduct->setData('surprise_drop_id', $surpriseDropId);
                            $modelProduct->setData('product_id', $product);
                            $modelProduct->save();
                        }
                    }

                    if (count($restrictedProducts)) {
                        $this->messageManager->addErrorMessage(
                            __('product(s) %1 already assigned to another surprise drop event.',
                                implode(",", $restrictedProducts))
                        );
                    }
                }

                $this->messageManager->addSuccessMessage(__('You saved the surprise drop event.'));
                $this->dataPersistor->clear('born_surprisedrop');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Record.'));
            }

            $this->dataPersistor->set('born_surprisedrop', $data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
