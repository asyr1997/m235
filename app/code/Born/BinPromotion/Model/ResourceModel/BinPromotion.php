<?php
 /**
 * @category  Born_BinPromotion
 * @author    Kavya Perudi <kavya.perudi@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 */
namespace Born\BinPromotion\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class BinPromotion
 */
class BinPromotion extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bin_promotion', 'entity_id');
    }
}
