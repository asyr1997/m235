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

namespace Born\MopRestriction\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class MopRestrictionProducts
 * Born\MopRestriction\Model
 */
class MopRestrictionProducts extends AbstractModel
{
    /**
     * CMS page cache tag
     */
    protected const CACHE_TAG = 'bron_moprestriction_product';

    /**
     * @var string
     */
    protected $_cacheTag = 'bron_moprestriction_product';

    /**
     * @var string
     */
    protected $_eventPrefix = 'bron_moprestriction_product';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\MopRestrictionProducts::class);
    }
}
