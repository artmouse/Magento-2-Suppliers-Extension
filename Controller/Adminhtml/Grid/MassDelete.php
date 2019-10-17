<?php
namespace Informatics\Suppliers\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Informatics\Suppliers\Model\ResourceModel\Grid\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;


class MassDelete extends \Magento\Backend\App\Action
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
        
        $deleteids = $this->getRequest()->getPost('id');        
       
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('id',array('in'=>$deleteids));
        $delete = 0;
        foreach ($collection as $item) {
            $item->delete();
            $delete++;
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $delete));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}