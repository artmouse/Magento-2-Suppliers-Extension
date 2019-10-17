<?php
 
namespace Informatics\Suppliers\Controller\Adminhtml\Grid;
 
use Magento\Backend\App\Action;
use Informatics\Suppliers\Model\SupplierproductsFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;
 
class Sendmail extends \Magento\Backend\App\Action
{
   
    protected $_coreRegistry = null;

    private $productCollectionFactory;

    protected $contactFactory;

    protected $transportBuilder;

    protected $inlineTranslation;

    protected $customHelper;
 
    protected $resultPageFactory;
 
  
    public function __construct(
        Action\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        SupplierproductsFactory $contactFactory,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        \Informatics\Suppliers\Helper\Data $customHelper,
        \Magento\Framework\Registry $registry
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->contactFactory = $contactFactory;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->customHelper = $customHelper;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
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

    protected function _isAllowed()
    {
        return true;
    }

    public function execute()
    {
         
       
        $id = $this->getRequest()->getParam('id');


        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('sku');
        $collection->addAttributeToSelect('price');
        $collection->addAttributeToFilter('ift_supplier_code', $this->getRequest()->getParam('id'));

        /*****/
        
        $collection->getSelect()->join( array('a'=> 'informatics_supplier_products'), 'a.productid = e.entity_id', array('a.ordercount'));
        
        /********/
        #echo $collection->getSelect();exit;

        /*$model = $this->_objectManager->create('Informatics\Suppliers\Model\Supplierproducts');
 
        if ($id) {
                 
            $selected = $model->getProducts($this->getRequest()->getParam('id'));
        
            if (!is_array($selected)) {
                $selected = [];
            }

        }
        */
        $_helper = $this->customHelper;

        $reportz = array();
        foreach($collection as $prdct)
        {
            $mail = $_helper->getSupplierEmail($id);
            $sname = $_helper->getSupplierName($id);
            $sphone = $_helper->getSupplierContact($id);
            $saddress = $_helper->getSupplierAddress($id);
            $qty  = $_helper->getStockQty($prdct->getId());
            //$pend = $_helper->getPendingOrderByProductId($prdct);
            //$availableNow = $_helper->getQtyByProductId($prdct);
            //$this->_reports[] = ['productid'=> $prdct, 'sold-quantity' => $sold, 'now-available' => $availableNow]; //$suppliers->getSuppid()
            $reportz[] = ["productid"=> $prdct->getId(), "productname"=> $prdct->getName(), "ordercount" => $prdct->getOrdercount(), 'now-available' => $qty];
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
                    ->setTemplateVars(['report' => $postObject, 'supplier_name' => $sname, 'supplier_mail' => $mail, 'supplier_phone' => $sphone, 'supplier_address' => $saddress])
                    ->setFrom(['name' => 'SOUQERZ','email' => 'info@souqerz.com'])
                    ->addTo([$mail])
                    ->getTransport();

                $transport->sendMessage();
                $this->inlineTranslation->resume();

                $this->messageManager->addSuccess(__('Report Successfully Sent.'));
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