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

namespace Born\MopRestriction\Block\Adminhtml;

/**
 * Class MopRestriction
 * Born\MopRestriction\Block\Adminhtml
 */
class MopRestriction extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        $this->_controller = 'adminhtml_moprestriction';
        $this->_blockGroup = 'Born_MopRestriction';
        $this->_headerText = __('Manage Mop Restriction');
        $this->_addButtonLabel = __('Add New');
        parent::_construct();
        if ($this->_isAllowedAction('Born_MopRestriction::save')) {
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
