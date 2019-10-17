<?php
namespace Informatics\Suppliers\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Sales order place  observer
 */
class DataOrderPlace implements ObserverInterface 
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;
    
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_supplierManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $_logger;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;




    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order $order,
        \Informatics\Suppliers\Model\SupplierproductsFactory $supplierManager,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\ProductFactory $productFactory
    )
    {       
        $this->_customerSession = $customerSession;
        $this->_order = $order;
        $this->_supplierManager = $supplierManager;
        $this->_logger = $logger;
        $this->_productFactory = $productFactory;
    }
    /**
     * Update items stock status and low stock date.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /*
        $order = $observer->getOrder();
        $order->setCanSendNewEmailFlag(false);
        */

        $order = $observer->getEvent()->getOrder();

        $incrementId = $order->getIncrementId(); #echo $incrementId;exit; 
        #$this->_logger->warning("Hi creato un Log con l'observer ". $incrementId);
        $grandTotal  = $order->getGrandTotal();
        $payment     = $order->getPayment();
        $ordrStatus  = $order->getStatus(); 

        $product = $this->_productFactory->create();

        foreach($order->getAllVisibleItems() as $item ) {
          
            $productColl = $product->load($item->getProductId());

            //$productColl->getData("ift_supplier_code");
            $_productId = $item->getProductId();
            #$this->_logger->warning("Ho creato un Log con l'observer");

            if($_productId) {

                $_supplierId = $productColl->getIftSupplierCode();
                $_itemCount = $item->getQtyOrdered();
            }
            #echo $_supplierId . " <> " . $_itemCount; exit;

            if($_supplierId) {

                $supplierAccount = $this->_supplierManager->create()->load($_productId,'productid');
                $existCount = $supplierAccount->getData('ordercount');
                
                if($existCount <> 0 || $existCount <> "") {

                    $totalCount = $existCount + $_itemCount;
                    $supplierAccount->setData('ordercount', $totalCount)->save();
                    #$this->_logger->warning("Ho creato un Log con l'observer");
                    
                } else {

                    $supplierAccount->setData('ordercount', $_itemCount)->save();
                    #$this->_logger->warning("Ho creato un Log con l'observer");
                }
            }
        }
    }
}
