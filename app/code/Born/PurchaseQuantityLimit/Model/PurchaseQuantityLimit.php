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

namespace Born\PurchaseQuantityLimit\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class PurchaseQuantityLimit
 * Born\PurchaseQuantityLimit\Model
 */
class PurchaseQuantityLimit extends AbstractModel
{
    /**
     * CMS page cache tag
     */
    protected const CACHE_TAG = 'bron_purchasequantity_limit';

    /**
     * @var string
     */
    protected $_cacheTag = 'bron_purchasequantity_limit';

    /**
     * @var string
     */
    protected $_eventPrefix = 'bron_purchasequantity_limit';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\PurchaseQuantityLimit::class);
    }
}
