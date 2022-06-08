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
namespace Born\SurpriseDrop\Model\ResourceModel\SurpriseDropProducts;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * Born\SurpriseDrop\Model\ResourceModel\SurpriseDropProducts
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
    protected $_eventPrefix = 'born_surprise_drop_products';

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
            \Born\SurpriseDrop\Model\SurpriseDropProducts::class,
            \Born\SurpriseDrop\Model\ResourceModel\SurpriseDropProducts::class
        );
    }
}
