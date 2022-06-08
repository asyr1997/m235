<?php
/**
 * @category  Born_BinPromotion
 * @author    Kavya Perudi <kavya.perudi@borngroup.com>
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 */

namespace Born\BinPromotion\Model;

use Magento\Framework\Model\AbstractModel;

 /**
 * Class BinPromotion
 */
class BinPromotion extends AbstractModel
{
    const CACHE_TAG = 'id';

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Born\BinPromotion\Model\ResourceModel\BinPromotion');
    }

    /**
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
