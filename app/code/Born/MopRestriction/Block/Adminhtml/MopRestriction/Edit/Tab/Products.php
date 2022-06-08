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

namespace Born\MopRestriction\Block\Adminhtml\MopRestriction\Edit\Tab;

use Born\MopRestriction\Model\MopRestrictionProductsFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Registry;

/**
 * Class Products
 * Born\MopRestriction\Block\Adminhtml\MopRestriction\Edit\Tab
 */
class Products extends Extended
{
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var MopRestrictionProductsFactory
     */
    private $mopRestrictionProductsFactory;

    /**
     * Products constructor.
     * @param Context $context
     * @param Data $backendHelper
     * @param Registry $registry
     * @param MopRestrictionProductsFactory $mopRestrictionProductsFactory
     * @param CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Registry $registry,
        MopRestrictionProductsFactory $mopRestrictionProductsFactory,
        CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->registry = $registry;
        $this->mopRestrictionProductsFactory = $mopRestrictionProductsFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Construct
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('id')) {
            $this->setDefaultFilter(['in_product' => 1]);
        }
    }

    /**
     * AddColumnFilterToCollection
     *
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    /**
     * Prepare Collection
     *
     * @return Products
     */
    public function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepared Columns
     *
     * @return $this
     * @throws \Exception
     */
    public function _prepareColumns()
    {
        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->_getSelectedProducts(),
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'names',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Grid Url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }

    /**
     * Row Url
     *
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    /**
     * Selected Products
     *
     * @return mixed
     */
    public function _getSelectedProducts()
    {
        return $this->getPurchaseQuantityLimitProducts();
    }

    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        return $this->getPurchaseQuantityLimitProducts();
    }

    /**
     * PurchaseQuantityLimit
     *
     * @return mixed
     */
    public function getPurchaseQuantityLimitProducts()
    {
        $mopRestrictionId = $this->getRequest()->getParam('id');
        return $this->mopRestrictionProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('mop_id', (int)$mopRestrictionId)
            ->setOrder('product_id', 'ASC')
            ->getColumnValues('product_id');
    }

    /**
     * Show Tab
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return true;
    }
}
