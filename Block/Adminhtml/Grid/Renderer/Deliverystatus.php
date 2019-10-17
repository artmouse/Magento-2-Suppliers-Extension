<?php

namespace Informatics\Suppliers\Block\Adminhtml\Grid\Renderer;

use Magento\Framework\DataObject;

class Deliverystatus extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    public function render(DataObject $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        /*
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $body = $objectManager->get('Prithweema\CertificateBody\Model\Certificatebody')->load($value);
        return $body->getCertificateBody();
        */
        if ($value==0) {
        	$status = "Not Delivered";
        } else {
        	$status = "Delivered";
        }

        return $status;
    }
}