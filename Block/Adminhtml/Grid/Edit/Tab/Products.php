<?php

namespace Informatics\Suppliers\Block\Adminhtml\Grid\Edit\Tab;

use Informatics\Suppliers\Model\SupplierproductsFactory;

/**
 * Class Products
 * @package Prince\Productattach\Block\Adminhtml\Productattach\Edit\Tab
 */
class Products extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Informatics\Suppliers\Model\Supplierproducts
     */
    private $attachModel;

    /**
     * @var SupplierproductsFactory
     */
    private $contactFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Products constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Framework\Registry $registry
     * @param SupplierproductsFactory $contactFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Informatics\Suppliers\Model\Supplierproducts $attachModel
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        SupplierproductsFactory $contactFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Informatics\Suppliers\Model\Supplierproducts $attachModel,
        array $data = []
    ) {
        $this->contactFactory = $contactFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->registry = $registry;
        $this->attachModel = $attachModel;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('productsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('id')) {
            $this->setDefaultFilter(['in_product' => 1]);
        }
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    public function _addColumnFilterToCollection($column)
    {
        /*
        if ($column->getId() == 'in_product') {
            $productIds = $this->_getSelectedProducts(); //print_r($productIds); echo " forced>>"; exit;

            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }*/
        $productIds = $this->_getSelectedProducts(); //print_r($productIds); echo " forced>>"; exit;

        if (empty($productIds)) {
            $productIds = 0;
        }
        if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
        } else {
            if ($productIds) {
                $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
            }
        }
        return $this;
    }

    /**
     * prepare collection
     */
    public function _prepareCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $collection->addAttributeToFilter('ift_supplier_code', $this->getRequest()->getParam('id'));

        /*****/
        
        $collection->getSelect()->join( array('a'=> 'informatics_supplier_products'), 'a.productid = e.entity_id', array('a.ordercount', 'a.isdelivered'));
        #$collection->getSelect()->join( array('b'=> 'informatics_supplier_product_logs'), 'b.productid = e.entity_id', array('b.productcount', 'b.deliverydate'));
        $this->setCollection($collection);
        
        /********/
        #echo $collection->getSelect();exit;

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    public function _prepareColumns()
    {

        $model = $this->attachModel;

        $this->addColumn(
            'in_product',
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'in_product',
                'align' => 'center',
                'index' => 'entity_id',
                'values' => $this->_getSelectedProducts(),
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        $this->addColumn(
            'names',
            [
                'header' => __('Name'),
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );


        $this->addColumn(
            'ordercount',
            [
                'header' => __('Current Orders'),
                'align' => 'center',
                'index' => 'ordercount',
                'filter' => false,
            ]
        );

        $this->addColumn(
            'isdelivered',
            [
                'header' => __('Delivery Status'),
                'align' => 'center',
                'index' => 'isdelivered',
                'filter' => false,
                'renderer' => 'Informatics\Suppliers\Block\Adminhtml\Grid\Renderer\Deliverystatus',
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }

    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    public function _getSelectedProducts()
    {
        $contact = $this->getContact();
        //$selected = $contact->getProducts($contact);
        $selected = $contact->getProducts($this->getRequest()->getParam('id'));
        
        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
        //$this->getRequest()->getParam('id');
        /*$vProducts = $this->contactFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('supplierid', $this->getRequest()->getParam('id')) 
                        ->addFieldToSelect('productid');

        $products = array(); 

        foreach($vProducts as $pdct){      
            $products[]  = $pdct->getProductid();
        }

        return $products; */

    }

    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $contact = $this->getContact();
        //$selected = $contact->getProducts($contact);
        $selected = $contact->getProducts($this->getRequest()->getParam('id'));
        
        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
        #echo $this->getRequest()->getParam('id');
        /*$vProducts = $this->contactFactory->create()
                        ->getCollection()
                        ->addFieldToFilter('supplierid', $this->getRequest()->getParam('id')) 
                        ->addFieldToSelect('productid');

        $products = array();

        foreach($vProducts as $pdct){      
            $products[]  = $pdct->getProductid();
        }
        //print_r($products); exit;
        return $products; */

    }

    public function getContact()
    {
        $contactId = $this->getRequest()->getParam('id');
        $contact   = $this->contactFactory->create();
        if ($contactId) {
            //echo 123; exit;
            $contact->load($contactId);
        }
        return $contact;
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return true;
    }
}
