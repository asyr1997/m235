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

/**
 * Class Index
 * Born\MopRestriction\Controller\Adminhtml\MopRestriction
 */
class Index extends \Magento\Backend\App\Action
{
    public const ADMIN_RESOURCE = 'Born_MopRestriction::born_moprestriction';
    /**
     * @var bool|\Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory = false;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * List action
     *
     * @return \Magento\Framework\App\ResponseInterface
     * @return \Magento\Framework\Controller\ResultInterface
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Born_MopRestriction::born_moprestriction');
        $resultPage->getConfig()->getTitle()->prepend((__('Manage MopRestriction')));
        return $resultPage;
    }
}
