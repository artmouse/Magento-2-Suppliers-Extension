<?php
 
namespace Informatics\Suppliers\Controller\Adminhtml\Grid;
 
use Magento\Backend\App\Action;
use Informatics\Suppliers\Model\SupplierproductsFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;
 
class Report extends \Magento\Backend\App\Action
{
   
    protected $_coreRegistry = null;

    protected $contactFactory;

    protected $transportBuilder;

    protected $inlineTranslation;

    protected $customHelper;
 
    protected $resultPageFactory;
 
  
    public function __construct(
        Action\Context $context,
        SupplierproductsFactory $contactFactory,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \Informatics\Suppliers\Helper\Data $customHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->contactFactory = $contactFactory;
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

        #$this->_reports = [['productid'=>'first', 'soldquantity'=>'second']];
        //$reportz = [['productid'=>'PRODUCT ID', 'soldquantity'=>'SOLD QUANTITY']];
        //$reportz = array("productid"=> "PRODUCT ID", "soldquantity"=>"SOLD QUANTITY");
        $reportz = array();
        foreach($selected as $prdct)
        {
            $sold = $_helper->getSoldQtyByProductId($prdct);
            $data = $_helper->getDetailsByProductId($prdct);
            $mail = $_helper->getSupplierEmail($id);
            $qty  = $_helper->getStockQty($prdct);
            $pend = $_helper->getPendingOrderByProductId($prdct);
            //$availableNow = $_helper->getQtyByProductId($prdct);
            //$this->_reports[] = ['productid'=> $prdct, 'sold-quantity' => $sold, 'now-available' => $availableNow]; //$suppliers->getSuppid()
            $reportz[] = ["productid"=> $prdct, "productname"=> $data, "soldquantity" => $sold, 'now-available' => $qty, 'pending-order-count'=> $pend];
        }
        #print_r($reportz); 
        #echo $pend; exit;

        $report =   [
                        'report_date' => date("j F Y", strtotime('-1 day')),
                        'report' => $reportz
                    ];

            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($reportz);

            try
            {
                $this->inlineTranslation->suspend();
                $transport = $this->transportBuilder
                    ->setTemplateIdentifier('supplier_report_template')
                    ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND, 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                    ->setTemplateVars(['report' => $postObject])
                    ->setFrom(['name' => 'SOUQERZ','email' => 'info@souqerz.com'])
                    ->addTo([$mail])
                    ->getTransport();

                $transport->sendMessage();
                $this->inlineTranslation->resume();

                $this->messageManager->addSuccess(__('LPO Successfully Sent.'));
                return $this->_redirect('*/*/');

            } catch(\Exception $e) {
                $this->messageManager->addError(__('Nothing to Send (Or) Failed'));
                return $this->_redirect('*/*/');
                echo $e->getMessage();
                #exit;   
            }

        #print_r($this->_reports);exit;

    }
}