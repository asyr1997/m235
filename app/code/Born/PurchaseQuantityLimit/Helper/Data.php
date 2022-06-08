<?php
/**
 * @package   Born_PurchaseQuantityLimit
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\PurchaseQuantityLimit\Helper;

use Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit;
use Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitFactory;
use Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimitProductsFactory;
use Born\SurpriseDrop\Model\SurpriseDrop;
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
 * Born\PurchaseQuantityLimit\Helper
 */
class Data extends AbstractHelper
{

    /**
     * @var PurchaseQuantityLimitFactory
     */
    private $purchaseQuantityLimitFactory;
    /**
     * @var PurchaseQuantityLimitProductsFactory
     */
    private $purchaseQuantityLimitProductsFactory;
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
     * @param PurchaseQuantityLimitFactory $purchaseQuantityLimitFactory
     * @param PurchaseQuantityLimitProductsFactory $purchaseQuantityLimitProductsFactory
     * @param OrderFactory $orderFactory
     * @param TimezoneInterface $timezoneInterface
     * @param ResourceConnection $resourceConnection
     * @param UrlInterface $backendUrl
     */
    public function __construct(
        Context                     $context,
        PurchaseQuantityLimitFactory         $purchaseQuantityLimitFactory,
        PurchaseQuantityLimitProductsFactory $purchaseQuantityLimitProductsFactory,
        OrderFactory                $orderFactory,
        TimezoneInterface           $timezoneInterface,
        ResourceConnection          $resourceConnection,
        UrlInterface                $backendUrl
    ) {
        parent::__construct($context);
        $this->purchaseQuantityLimitFactory = $purchaseQuantityLimitFactory;
        $this->purchaseQuantityLimitProductsFactory = $purchaseQuantityLimitProductsFactory;
        $this->orderFactory = $orderFactory;
        $this->timezoneInterface = $timezoneInterface;
        $this->resourceConnection = $resourceConnection;
        $this->backendUrl = $backendUrl;
    }
    /**
     * Get PurchaseQuantityLimit collection by ID
     * @return PurchaseQuantityLimit|AbstractDb|AbstractCollection
     */
    public function getPurchaseQuantityLimit($purchaseQuantityLimitId)
    {
        $now = $this->timezoneInterface->date();
        return $this->purchaseQuantityLimitFactory->create()
            ->getCollection()
            ->addFieldToFilter('id', (int)$purchaseQuantityLimitId)
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('start_date', ['lteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('end_date', ['gteq' => $now->format('Y-m-d H:i:s')]);
    }

    /**
     * Get PurchaseQuantityLimit Products by ID
     * @return array
     */
    public function getpurchaseQuantityLimitProducts($purchaseQuantityLimitId)
    {
        return $this->purchaseQuantityLimitProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('rule_id', (int)$purchaseQuantityLimitId)
            ->setOrder('product_id', 'ASC')
            ->getColumnValues('product_id');
    }

    /**
     * Check if product assigned to surprise drop
     * @param $productId
     * @param array $cartItems
     * @return array
     */
    /**
     * Check if product assigned to Purchase Quantity Rule
     *
     * @param $productId
     * @return array
     */
    public function checkIfProductAssignedToPurchaseQuantityLimit($productId)
    {
        $sdProductCollection = $this->purchaseQuantityLimitProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('product_id', (int)$productId)
            ->getColumnValues('rule_id');

        $response = ['success' => false, 'rule_id' => null, 'products' => null];
        foreach ($sdProductCollection as $purchaseQuantityLimit) {
            $sdCollection = $this->getPurchaseQuantityLimit($purchaseQuantityLimit);
            if ($sdCollection->getSize()) {
                $response['success'] = true;
                $response['rule_id'] = $purchaseQuantityLimit;
                $response['products'] = $this->getPurchaseQuantityLimitProducts($purchaseQuantityLimit);
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
        return $this->backendUrl->getUrl('purchasequantitylimit/purchasequantitylimit/products', ['_current' => true]);
    }
}
