<?php
namespace Informatics\Suppliers\Model\ResourceModel;
 
class Grid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    
    protected function _construct()
    {
        $this->_init('informatics_suppliers', 'id');
    }
}
