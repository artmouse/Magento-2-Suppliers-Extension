<?php

namespace Informatics\Suppliers\Model;

use Magento\Framework\Model\AbstractModel;

class Supplierproducts extends \Magento\Framework\Model\AbstractModel
{
	/**
     * CMS page cache tag
     */
    const CACHE_TAG = 'pt_products_grid';

    /**
     * @var string
     */
    protected $_cacheTag = 'pt_products_grid';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'pt_products_grid';

    /**
     * Initialize resource model
     *
     * @return void
     */

    protected function _construct()
    {
        $this->_init('Informatics\Suppliers\Model\ResourceModel\Supplierproducts');
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    //public function getProducts(\Informatics\Suppliers\Model\Supplierproducts $object)
    public function getProducts($supplierId)
    {
        //print_r($object); 
        //echo "hhh>> " . $supplierId; exit; 
        $tbl = $this->getResource()->getTable("informatics_supplier_products");
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['productid']
        )
        ->where(
            'supplierid = ?',
            (int)$supplierId
        ); //$object->getId()
        
        $products = $this->getResource()->getConnection()->fetchCol($select);
        /*
        if ($products) {
            $products = explode('&', $products[0]);
        }
        */
        return $products;
    }
}