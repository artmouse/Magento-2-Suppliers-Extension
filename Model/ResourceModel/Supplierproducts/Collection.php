<?php
namespace Informatics\Suppliers\Model\ResourceModel\Supplierproducts;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    
    protected function _construct()
    {
        $this->_init('Informatics\Suppliers\Model\Supplierproducts', 'Informatics\Suppliers\Model\ResourceModel\Supplierproducts');
    }

}