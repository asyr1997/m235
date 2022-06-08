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
namespace Born\PurchaseQuantityLimit\Model\ResourceModel\PurchaseQuantityLimitProducts;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * Born\PurchaseQuantityLimit\Model\ResourceModel\PurchaseQuantityLimitProducts
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * @var string
     */
    protected $_eventPrefix = 'born_purchasequantitylimit_products_collection';
    /**
     * @var string
     */
    protected $_eventObject = 'products_collection';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitProducts::class,
            \Born\PurchaseQuantityLimit\Model\ResourceModel\PurchaseQuantityLimitProducts::class
        );
    }
}
