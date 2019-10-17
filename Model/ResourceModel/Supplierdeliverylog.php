<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Informatics\Suppliers\Model\ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
class Supplierdeliverylog extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('informatics_supplier_product_logs', 'id');
    }
}