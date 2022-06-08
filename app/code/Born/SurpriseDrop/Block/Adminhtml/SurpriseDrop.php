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

namespace Born\SurpriseDrop\Block\Adminhtml;

/**
 * Class SurpriseDrop
 * Born\SurpriseDrop\Block\Adminhtml
 */
class SurpriseDrop extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_controller = 'adminhtml_surprisedrop';
        $this->_blockGroup = 'Born_SurpriseDrop';
        $this->_headerText = __('Surprise Drop Manage');
        $this->_addButtonLabel = __('Add New');
        parent::_construct();
        if ($this->_isAllowedAction('Born_SurpriseDrop::save')) {
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
