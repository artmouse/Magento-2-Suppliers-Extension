<?php
namespace Informatics\Suppliers\Block\Adminhtml\Grid\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
   
    protected function _construct()
    {
        parent::_construct();
        $this->setId('grid_record');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Supplier Information'));

         $this->addTab(
            'supplier_information',
            [
            'label' => __('Supplier Information'),
            'content' => $this->getLayout()->createBlock('Informatics\Suppliers\Block\Adminhtml\Grid\Edit\Tab\Main')->toHtml()
            ]
            );
        $this->addTab(
            'products',
            [
                'label' => __('Products'),
                'url' => $this->getUrl('suppliers/grid/products', ['_current' => true]),
                'class' => 'ajax'
            ]
        );
    }
}