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
namespace Born\MopRestriction\Model\ResourceModel\MopRestrictionProducts;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * Born\MopRestriction\Model\ResourceModel\MopRestrictionProducts
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
    protected $_eventPrefix = 'born_moprestriction_products_collection';
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
            \Born\MopRestriction\Model\MopRestrictionProducts::class,
            \Born\MopRestriction\Model\ResourceModel\MopRestrictionProducts::class
        );
    }
}
