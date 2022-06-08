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

namespace Born\PurchaseQuantityLimit\Block\Adminhtml;

/**
 * Class PurchaseQuantity
 * Born\PurchaseQuantityLimit\Block\Adminhtml
 */
class PurchaseQuantityLimit extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_controller = 'adminhtml_purchasequantity';
        $this->_blockGroup = 'Born_PurchaseQuantityLimit';
        $this->_headerText = __('Manage Purchase Quantity Limit');
        $this->_addButtonLabel = __('Add New');
        parent::_construct();
        if ($this->_isAllowedAction('Born_PurchaseQuantityLimit::save')) {
            $this->buttonList->update('add', 'label', __('Add New'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
