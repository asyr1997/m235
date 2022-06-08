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

namespace Born\SurpriseDrop\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class SurpriseDrop
 * Born\SurpriseDrop\Model
 */
class SurpriseDrop extends AbstractModel
{
    /**
     * CMS page cache tag
     */
    protected const CACHE_TAG = 'bron_surprise_drop';

    /**
     * @var string
     */
    protected $_cacheTag = 'born_surprise_drop';

    /**
     * @var string
     */
    protected $_eventPrefix = 'born_surprise_drop';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\SurpriseDrop::class);
    }
}
