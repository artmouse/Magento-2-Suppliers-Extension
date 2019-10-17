<?php

namespace Informatics\Suppliers\Observer;

use Magento\Framework\Event\ObserverInterface;

class Productsaveafter implements ObserverInterface
{    
	/**
    * @var \Informatics\Suppliers\Model\SuppliersFactory
    */
    protected $_suppliersFactory;

    /**
    * @var \Informatics\Suppliers\Model\SuppliersFactory
    */
    protected $_supplierproductsFactory;


    public function __construct(
        \Informatics\Suppliers\Model\SuppliersFactory $suppliersFactory,
        \Informatics\Suppliers\Model\SupplierproductsFactory $supplierproductsFactory
    )
    {       
        $this->_suppliersFactory = $suppliersFactory;
        $this->_supplierproductsFactory = $supplierproductsFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_product = $observer->getProduct();  // you will get product object

        $_supplierId = $_product->getIftSupplierCode(); //$_product->getSku(); // for sku
        $_productId  = $_product->getId();

        if($_supplierId <> "") {
        	#echo "here " . $_supplierId . " >>"; exit;

        	$supplierData       = $this->_suppliersFactory->create()->load($_supplierId,'id');
        	$supplierProdctData = $this->_supplierproductsFactory->create()->load($_productId,'productid');
        	//echo $supplierProdctData->getId(); exit;
        	try {

	        	if (!$supplierProdctData->getId()) {

	        		#echo 123;exit;

	        		$supplierProdctInsert = $this->_supplierproductsFactory->create();

	        		$dataSupplierProducts = array(
							                        'supplierid' => $_supplierId,
							                        'productid'  => $_productId
							                    );

	        		$supplierProdctInsert->setData($dataSupplierProducts)->save();

	        	} else {

	        		$supplierProdctId = $supplierProdctData->getId();

	        		#echo $supplierProdctId . ' 456';

	        		$supplierProdctUpdate = $this->_supplierproductsFactory->create()->load($supplierProdctId,'id');

	        		$dataSupplierProducts = array(
							                        'supplierid' => $_supplierId,
							                        'productid'  => $_productId
							                    );
	        		
	        		$supplierProdctUpdate->addData($dataSupplierProducts)->save();

	        	}

            } catch(\Exception $e) {

                $this->messageManager->addError(
                    __('We can\'t process your request right now. Sorry, that\'s all we know.')
                );
                    
            }

        }

    }   
}