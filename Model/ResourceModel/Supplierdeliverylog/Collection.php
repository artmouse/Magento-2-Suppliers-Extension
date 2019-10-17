<?php
namespace Informatics\Suppliers\Model\ResourceModel\Supplierdeliverylog;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    
    protected function _construct()
    {
        $this->_init('Informatics\Suppliers\Model\Supplierdeliverylog', 'Informatics\Suppliers\Model\ResourceModel\Supplierdeliverylog');
    }

}