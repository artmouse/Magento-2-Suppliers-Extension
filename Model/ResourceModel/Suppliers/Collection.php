<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Informatics\Suppliers\Model\ResourceModel\Suppliers;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection{
    protected function _construct()
    {
        $this->_init('Informatics\Suppliers\Model\Suppliers', 'Informatics\Suppliers\Model\ResourceModel\Suppliers');
    }
}