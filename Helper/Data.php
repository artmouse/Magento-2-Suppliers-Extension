<?php

namespace Informatics\Suppliers\Helper;

use Magento\CatalogInventory\Model\Stock\StockItemRepository as StockItem;
use Magento\Catalog\Model\Product as Product;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_reportCollectionFactory;
    protected $stockItem;
    protected $product;
    protected $_storeManager;
    protected $_supplierManager;
    protected $_order;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StockItem $stockItem,
        Product $product,
        \Informatics\Suppliers\Model\SuppliersFactory $supplierManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $order,
        \Magento\Reports\Model\ResourceModel\Product\Sold\CollectionFactory $reportCollectionFactory
    ) {
        $this->stockItem = $stockItem;
        $this->product = $product;
        $this->_supplierManager = $supplierManager;
        $this->_storeManager = $storeManager;
        $this->_order  = $order;
        $this->_reportCollectionFactory = $reportCollectionFactory;
        parent::__construct($context);
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getSoldQtyByProductId($producID=null) {

        $SoldProducts= $this->_reportCollectionFactory->create();
        #$SoldProdudctCOl=$SoldProducts->addOrderedQty()->addAttributeToFilter('product_id', $producID);
        $SoldProdudctCOl=$SoldProducts->addOrderedQty()->addAttributeToFilter('product_id', $producID)->addAttributeToFilter('state', 'complete');
        /* If does have any product id 
         * then return false
         */
        if(!$SoldProdudctCOl->count()):
                    return false;
        endif;
        //echo $SoldProdudctCOl->getSelect()->__toString();

        $product = $SoldProdudctCOl
                 ->getFirstItem();

        return (int)$product->getData('ordered_qty');

    }

    public function getPendingOrderByProductId($producID=null) {

        $orderCollection = $this->_order->create();
        $pending = array();
        $product_id = $producID;
        $orderCollection->getSelect()
            ->join(
                'sales_order_item',
                'main_table.entity_id = sales_order_item.order_id'
            )->where('product_id = '.$product_id);

        $orderCollection->getSelect()->group('main_table.entity_id');
        $orderCollection->addFieldToFilter('status', array('in' => array('pending')));
        foreach ($orderCollection as $order) {
            $pending[] = $order->getIncrementId();
        }

        return (int) count($pending);

    }

    public function getQtyByProductId($producID=null) {

        if($producID){
            $productStock = $this->stockItem->get($producID);
            $productQty = $productStock->getQty();
        }

        return (int)$productQty;
        
    }

    public function getSupplierName($supplierID=null) {

        if($supplierID){
            $supplierAccount   = $this->_supplierManager->create()->load($supplierID,'id');
            $supplierName      = $supplierAccount->getData('name');
        }

        return $supplierName;
        
    }

    public function getSupplierContact($supplierID=null) {

        if($supplierID){
            $supplierAccount      = $this->_supplierManager->create()->load($supplierID,'id');
            $supplierContact      = $supplierAccount->getData('title');
        }

        return $supplierContact;
        
    }

    public function getSupplierAddress($supplierID=null) {

        if($supplierID){
            $supplierAccount      = $this->_supplierManager->create()->load($supplierID,'id');
            $supplierAddress      = $supplierAccount->getData('content');
        }

        return $supplierAddress;
        
    }


    public function getSupplierEmail($supplierID=null) {

        if($supplierID){
            $supplierAccount      = $this->_supplierManager->create()->load($supplierID,'id');
            $supplierEmailId      = $supplierAccount->getData('user');
        }

        return $supplierEmailId;
        
    }

    public function getStockQty($producID=null) {

        if($producID){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $stockInfo = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface')->getStockItem($producID);
            $stockqty = (int)$stockInfo->getQty();
        }

        return $stockqty;
        
    }

    public function getDetailsByProductId($producID=null) {

        $storeId = $this->getStoreId();
        return $this->product->getResource()->getAttributeRawValue($producID, 'name', $storeId);

    }
}