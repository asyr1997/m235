<?php
/**
 * @category  Born_BinPromotion
 * @author    Kavya Perudi <kavya.perudi@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 */
namespace Born\BinPromotion\Controller\Adminhtml\Index;

 use Magento\Backend\App\Action;
 use Magento\Backend\App\Action\Context;
 use Magento\Framework\App\ResponseInterface;
 use Magento\Framework\Controller\ResultInterface;
 use Magento\Framework\View\Result\Page;
 use Magento\Framework\View\Result\PageFactory;

 /**
  * Class Index
  */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory

    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Bin Numbers'));
        return $resultPage;
    }
}
