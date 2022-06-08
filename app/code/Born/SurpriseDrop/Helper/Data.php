<?php
/**
 * @package   Born_SurpriseDrop
 * @author    Manish Bhojwani (Manish.Bhojwani@BornGroup.com)
 * @copyright 2022 Copyright Born Group, Inc. http://www.borngroup.com/
 * @license   http://www.borngroup.com/ Private
 * @link      http://www.borngroup.com/
 */
namespace Born\SurpriseDrop\Helper;

use Born\SurpriseDrop\Model\SurpriseDrop;
use Born\SurpriseDrop\Model\SurpriseDropFactory;
use Born\SurpriseDrop\Model\SurpriseDropProductsFactory;
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
 * Born\SurpriseDrop\Helper
 */
class Data extends AbstractHelper
{
    /**
     * Configuration constants.
     */
    const PATH_SURPRISEDROP_IS_ENABLED                  = 'surprisedrop/general/is_enable';
    const PATH_SURPRISEDROP_MESSAGE_QUANTITY_LIMIT      = 'surprisedrop/message/quantity_limit';
    const PATH_SURPRISEDROP_MESSAGE_ALREADY_ADDED       = 'surprisedrop/message/already_added';
    const PATH_SURPRISEDROP_MESSAGE_ALREADY_PURCHASED   = 'surprisedrop/message/already_purchased';
    const PATH_SURPRISEDROP_MESSAGE_CUSTOMER_LOGIN      = 'surprisedrop/message/login';
    const PATH_SURPRISEDROP_MESSAGE_CUSTOMER_REORDER    = 'surprisedrop/message/reorder';

    /**
     * @var SurpriseDropFactory
     */
    private $surpriseDropFactory;
    /**
     * @var SurpriseDropProductsFactory
     */
    private $surpriseDropProductsFactory;
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
     * @param SurpriseDropFactory $surpriseDropFactory
     * @param SurpriseDropProductsFactory $surpriseDropProductsFactory
     * @param OrderFactory $orderFactory
     * @param TimezoneInterface $timezoneInterface
     * @param ResourceConnection $resourceConnection
     * @param UrlInterface $backendUrl
     */
    public function __construct(
        Context                     $context,
        SurpriseDropFactory         $surpriseDropFactory,
        SurpriseDropProductsFactory $surpriseDropProductsFactory,
        OrderFactory                $orderFactory,
        TimezoneInterface           $timezoneInterface,
        ResourceConnection          $resourceConnection,
        UrlInterface                $backendUrl
    ) {
        parent::__construct($context);
        $this->surpriseDropFactory = $surpriseDropFactory;
        $this->surpriseDropProductsFactory = $surpriseDropProductsFactory;
        $this->orderFactory = $orderFactory;
        $this->timezoneInterface = $timezoneInterface;
        $this->resourceConnection = $resourceConnection;
        $this->backendUrl = $backendUrl;
    }

    /**
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param null $storeId
     * @return int
     */
    public function isEnabled($storeId = null)
    {
        return (int)$this->getConfigValue(self::PATH_SURPRISEDROP_IS_ENABLED, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getQuantityLimitMessage($storeId = null)
    {
        return $this->getConfigValue(self::PATH_SURPRISEDROP_MESSAGE_QUANTITY_LIMIT, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getAlreadyAddedMessage($storeId = null)
    {
        return $this->getConfigValue(self::PATH_SURPRISEDROP_MESSAGE_ALREADY_ADDED, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getAlreadyPurchasedMessage($storeId = null)
    {
        return $this->getConfigValue(self::PATH_SURPRISEDROP_MESSAGE_ALREADY_PURCHASED, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getCustomerLoginMessage($storeId = null)
    {
        return $this->getConfigValue(self::PATH_SURPRISEDROP_MESSAGE_CUSTOMER_LOGIN, $storeId);
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getCustomerReorderMessage($storeId = null)
    {
        return $this->getConfigValue(self::PATH_SURPRISEDROP_MESSAGE_CUSTOMER_REORDER, $storeId);
    }

    /**
     * Get Surprise Drop collection by ID
     * @return SurpriseDrop|AbstractDb|AbstractCollection
     */
    public function getSurpriseDrop($surpriseDropId)
    {
        $now = $this->timezoneInterface->date();
        return $this->surpriseDropFactory->create()
            ->getCollection()
            ->addFieldToFilter('id', (int)$surpriseDropId)
            ->addFieldToFilter('status', 1)
            ->addFieldToFilter('start_date', ['lteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('end_date', ['gteq' => $now->format('Y-m-d H:i:s')]);
    }

    /**
     * Get Surprise Drop Products by ID
     * @return array
     */
    public function getSurpriseDropProducts($surpriseDropId)
    {
        return $this->surpriseDropProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('surprise_drop_id', (int)$surpriseDropId)
            ->setOrder('product_id', 'ASC')
            ->getColumnValues('product_id');
    }

    /**
     * Check if product assigned to surprise drop
     * @param $productId
     * @param array $cartItems
     * @return array
     */
    public function checkIfProductAssignedToSurpriseDrop($productId)
    {
        $sdProductCollection = $this->surpriseDropProductsFactory->create()
            ->getCollection()
            ->addFieldToFilter('product_id', (int)$productId)
            ->getColumnValues('surprise_drop_id');

        $response = ['success' => false, 'surprise_drop_id' => null, 'products' => null];
        foreach ($sdProductCollection as $surpriseDrop) {
            $sdCollection = $this->getSurpriseDrop($surpriseDrop);
            if ($sdCollection->getSize()) {
                $response['success'] = true;
                $response['surprise_drop_id'] = $surpriseDrop;
                $response['products'] = $this->getSurpriseDropProducts($surpriseDrop);
                break;
            }
        }

        return $response;
    }

    /**
     * Check if customer already ordered surprise drop product.
     * @param $productId
     * @param array $cartItems
     * @return bool
     */
    public function checkIfCustomerOrderedSurpriseDrop($customerId, $surpriseDropId)
    {
        $connection = $this->resourceConnection->getConnection();
        $orderCollection = $this->orderFactory->create()
            ->getCollection()
            ->addFieldToFilter('customer_id', (int)$customerId);
        $orderCollection->getSelect()->join(
            ['soi' => $connection->getTableName('sales_order_item')],
            'main_table.entity_id = soi.order_id AND soi.surprise_drop_id = ' . $surpriseDropId,
            ['soi.surprise_drop_id']
        );

        if ($orderCollection->getSize()) {
            return  true;
        }

        return false;
    }

    /**
     * Get Product Data
     * @return string
     */
    public function getProductsGridUrl()
    {
        return $this->backendUrl->getUrl('surprisedrop/surprisedrop/products', ['_current' => true]);
    }
}
