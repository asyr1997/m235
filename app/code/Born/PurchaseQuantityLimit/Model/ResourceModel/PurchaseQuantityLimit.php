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
namespace Born\PurchaseQuantityLimit\Model\ResourceModel;

/**
 * Class PurchaseQuantityLimit
 * Born\PurchaseQuantityLimit\Model\ResourceModel
 */
class PurchaseQuantityLimit extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('born_quantity_limitor_rules', 'id');
    }
}
