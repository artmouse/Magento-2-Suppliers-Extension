<?php

namespace Informatics\Suppliers\Model;

use Magento\Framework\Model\AbstractModel;

class Suppliers extends \Magento\Framework\Model\AbstractModel
{

    protected function _construct()
    {
        $this->_init('Informatics\Suppliers\Model\ResourceModel\Suppliers');
    }
}
