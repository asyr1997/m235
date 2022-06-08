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

/**
 * controller to Edit record from admin.
 * Class Edit
 * Born\MopRestriction\Controller\Adminhtml\MopRestriction
 */
class Edit extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Born_MopRestriction::born_moprestriction';

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Born\MopRestriction\Model\MopRestrictionFactory $mopRestrictionFatory
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Born\MopRestriction\Model\MopRestrictionFactory $mopRestrictionFatory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->mopRestrictionFatory = $mopRestrictionFatory;
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
        $resultPage->setActiveMenu('Born_MopRestriction::born_moprestriction');
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
        $model = $this->mopRestrictionFatory->create();

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

        $this->_coreRegistry->register('born_moprestriction', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit') : __('New'),
            $id ? __('Edit') : __('New')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Mop Restriction'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __(''));

        return $resultPage;
    }
}
