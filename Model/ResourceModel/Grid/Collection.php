<?php
namespace Informatics\Suppliers\Model\ResourceModel\Grid;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
 
    
    protected function _construct()
    {
        $this->_init('Informatics\Suppliers\Model\Grid', 'Informatics\Suppliers\Model\ResourceModel\Grid');
    }

    public function getAvailableStatuses()
    {
       return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}