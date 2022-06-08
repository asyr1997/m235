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
namespace Born\SurpriseDrop\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class SurpriseDrop
 * Born\SurpriseDrop\Model\ResourceModel
 */
class SurpriseDrop extends AbstractDb
{
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('born_surprise_drop', 'id');
    }
}
