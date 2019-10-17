<?php
 
namespace Informatics\Suppliers\Controller\Adminhtml\Grid;
 
use Magento\Backend\App\Action;
use Informatics\Suppliers\Model\SupplierproductsFactory;
use Informatics\Suppliers\Model\SupplierdeliverylogFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;
 
class Delivered extends \Magento\Backend\App\Action
{
   
    protected $_coreRegistry = null;

    protected $contactFactory;

    protected $deliverylogFactory;

    protected $transportBuilder;

    protected $inlineTranslation;

    protected $customHelper;
 
    protected $resultPageFactory;
 
  
    public function __construct(
        Action\Context $context,
        SupplierproductsFactory $contactFactory,
        SupplierdeliverylogFactory $deliverylogFactory,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \Informatics\Suppliers\Helper\Data $customHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->contactFactory = $contactFactory;
        $this->deliverylogFactory = $deliverylogFactory;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->customHelper = $customHelper;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return true;
    }

    public function execute()
    {
         
       
        $id = $this->getRequest()->getParam('id');

        $model = $this->_objectManager->create('Informatics\Suppliers\Model\Supplierproducts');
 
        if ($id) {
                 
            $selected = $model->getProducts($this->getRequest()->getParam('id'));
        
            if (!is_array($selected)) {
                $selected = [];
            }

        }
 
        $_helper = $this->customHelper;
        

            try
            {
                foreach($selected as $prdct)
                {
                    $supplierproductAccount = $this->contactFactory->create()->load($prdct,'productid');
                    $ordrCount = $supplierproductAccount->getData('ordercount');
                    $entryId = $supplierproductAccount->getData('id');

                    $supplierproductUpdate = $this->contactFactory->create();
                    $supplierproductUpdate->load($entryId);
                    $supplierproductUpdate->setOrdercount(0);
                    $supplierproductUpdate->setIsdelivered(1);
                    $supplierproductUpdate->save();

                     $dataDeliverylog = array(
                                            'supplierid' => $id,
                                            'productid' => $prdct, // transferred amount
                                            'productcount' => $ordrCount
                                        );  
                           
                    $DeliverylogAccount = $this->deliverylogFactory->create();
                    $DeliverylogAccount->setData($dataDeliverylog)->save();
                    
                }

                $this->messageManager->addSuccess(__('Delivery Status Successfully Changed.'));
                return $this->_redirect('*/*/');

            } catch(\Exception $e) {
                $this->messageManager->addError(__('Failed...Try again'));
                return $this->_redirect('*/*/');
                echo $e->getMessage();
                #exit;   
            }

        #print_r($this->_reports);exit;

    }
}