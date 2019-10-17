<?php
namespace Informatics\Suppliers\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Informatics\Suppliers\Model\ResourceModel\Grid\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;


class MassStatus  extends \Magento\Backend\App\Action
{
    
    protected $filter;

    protected $collectionFactory;


    
    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }
  
    public function execute()
    {   
       
        $ids = $this->getRequest()->getPost('id');   
        $st=$this->getRequest()->getPost('status');   

      
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('id',array('in'=>$ids));

        $cnt=count($collection);

        $updateStatus = 0;  
        try {
                  foreach ($collection as $item) 
                  {
                     $item->setStatus($st)->save();
                     $updateStatus++;
                  }              
            } catch (Exception $e){                
            }
       
            
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been updated.',$updateStatus));

      
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}