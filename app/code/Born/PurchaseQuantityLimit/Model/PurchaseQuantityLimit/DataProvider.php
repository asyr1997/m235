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
namespace Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit;

use Born\PurchaseQuantityLimit\Model\ResourceModel\PurchaseQuantityLimit\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 * Born\PurchaseQuantityLimit\Model\PurchaseQuantityLimit
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Born\PurchaseQuantityLimit\Model\ResourceModel\PurchaseQuantityLimit\Collection
     */
    protected $collection;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var LoadedData
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Meta Data
     *
     * @param array $meta
     * @return array
     */

    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get Data
     *
     * @return array|LoadedData|null
     */
    public function getData()
    {
        $this->loadedData = null;

        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        if ($items) {
            foreach ($items as $item) {
                $this->loadedData[$item->getId()] = $item->getData();
            }
        }

        $data = $this->dataPersistor->get('purchasequantity_limit');
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $this->loadedData[$item->getId()] = $item->getData();
            $this->dataPersistor->clear('purchasequantity_limit');
        }

        return $this->loadedData;
    }
}
