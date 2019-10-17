<?php
 
namespace Informatics\Suppliers\Controller\Adminhtml\Grid;
 
use Magento\Backend\App\Action;
 
class Edit extends \Magento\Backend\App\Action
{
   
    protected $_coreRegistry = null;
 
    protected $resultPageFactory;
 
  
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
 
   
    protected function _isAllowed()
    {
        return true;
    }
 
   
    protected function _initAction()
    {

      
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Informatics_Suppliers::grid')
            ->addBreadcrumb(__('Suppliers'), __('Suppliers'))
            ->addBreadcrumb(__('Manage Suppliers'), __('Manage Suppliers'));
        return $resultPage;
    }
 
  
    public function execute()
    {
         
       
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create('Informatics\Suppliers\Model\Grid');
 
        if ($id) {

            $model->load($id);
            if (!$model->getId()) {
                 
                $this->messageManager->addError(__('This Suppliers record no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
 
                return $resultRedirect->setPath('*/*/');
            }
        }
 
    $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
   
        if (!empty($data)) {
            $model->setData($data);
        }
 
        $this->_coreRegistry->register('informatics_form_data', $model);
        

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Supplier Data') : __('New Supplier'),
            $id ? __('Edit Supplier Data') : __('New Supplier')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Grids'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('New Supplier')); 
        return $resultPage;
    }
}