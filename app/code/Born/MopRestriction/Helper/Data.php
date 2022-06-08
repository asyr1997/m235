<?php
/**
 * @package   Born_MopRestriction
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\MopRestriction\Helper;

use Born\MopRestriction\Model\MopRestriction;
use Born\MopRestriction\Model\MopRestrictionFactory;
use Born\MopRestriction\Model\MopRestrictionProductsFactory;
use Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * Born\MopRestriction\Helper
 */
class Data extends AbstractHelper
{

    /**
     * @var MopRestrictionFactory
     */
    private $mopRestrictionFactory;
    /**
     * @var MopRestrictionProductsFactory
     */
    private $mopRestrictionProductsFactory;
    /**
     * @var TimezoneInterface
     */
    private $timezoneInterface;
    /**
     * @var OrderFactory
     */
    private $orderFactory;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var UrlInterface
     */
    private $backendUrl;

    /**
     * @param Context $context
     * @param MopRestrictionFactory $mopRestrictionFactory
     * @param MopRestrictionProductsFactory $mopRestrictionProductsFactory
     * @param OrderFactory $orderFactory
     * @param TimezoneInterface $timezoneInterface
     * @param ResourceConnection $resourceConnection
     * @param UrlInterface $backendUrl
     */
    public function __construct(
        Context                     $context,
        MopRestrictionFactory         $mopRestrictionFactory,
        MopRestrictionProductsFactory $mopRestrictionProductsFactory,
        OrderFactory                $orderFactory,
        TimezoneInterface           $timezoneInterface,
        ResourceConnection          $resourceConnection,
        UrlInterface                $backendUrl
    ) {
        parent::__construct($context);
        $this->mopRestrictionFactory = $mopRestrictionFactory;
        $this->mopRestrictionProductsFactory = $mopRestrictionProductsFactory;
        $this->orderFactory = $orderFactory;
        $this->timezoneInterface = $timezoneInterface;
        $this->resourceConnection = $resourceConnection;
        $this->backendUrl = $backendUrl;
    }
    /**
     * Get MopRestriction collection by ID
     * @return MopRestriction|AbstractDb|AbstractCollection
     */
    public function getMopRestriction($mopRestrictionId)
    {
        $now = $this->timezoneInterface->date();
        return $this->mopRestrictionFactory->create()
            ->getCollection()
            ->addFieldToFilter('id', (int)$mopRestrictionId)
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('start_date', ['lteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('end_date', ['gteq' => $now->format('Y-m-d H:i:s')]);
    }

    /**
     * Get MopRestriction Products by ID
     * @return array
     */
    public function getmopRestrictionProducts($mopRestrictionId)
    {
        return $this->mopRestrictionProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('mop_id', (int)$mopRestrictionId)
            ->setOrder('product_id', 'ASC')
            ->getColumnValues('product_id');
    }

    /**
     * Check if product assigned to Purchase Quantity Rule
     *
     * @param $productId
     * @return array
     */
    public function checkIfProductAssignedToMopRestriction($productId)
    {
        $sdProductCollection = $this->mopRestrictionProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('product_id', (int)$productId)
            ->getColumnValues('mop_id');

        $response = ['success' => false, 'mop_id' => null, 'products' => null];
        foreach ($sdProductCollection as $mopRestriction) {
            $sdCollection = $this->getMopRestriction($mopRestriction);
            if ($sdCollection->getSize()) {
                $response['success'] = true;
                $response['mop_id'] = $mopRestriction;
                $response['products'] = $this->getmopRestrictionProducts($mopRestriction);
                break;
            }
        }

        return $response;
    }

    /**
     * Return stores
     *
     * @param $store
     * @return string
     */
    public function getStores($store)
    {
        $store = implode(',', $store);
        return $store;
    }

    /**
     * Get Product Data
     *
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->backendUrl->getUrl('moprestriction/moprestriction/products', ['_current' => true]);
    }
}
