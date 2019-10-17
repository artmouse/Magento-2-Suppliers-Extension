<?php
namespace Informatics\Suppliers\Block\Adminhtml\Grid;
 
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    protected $moduleManager;
   
    protected $_gridFactory; 
   
    protected $_status;   
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Informatics\Suppliers\Model\GridFactory $gridFactory,
        \Informatics\Suppliers\Model\Status $status,
        \Magento\Framework\Module\Manager $moduleManager,
        array $data = []
    ) {

        $this->_gridFactory = $gridFactory;
        $this->_status = $status;
        $this->moduleManager = $moduleManager;
        parent::__construct($context, $backendHelper, $data);
    } 
   
    protected function _construct()
    {
        parent::_construct();
        $this->setId('gridGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('grid_record');

    } 
   
    protected function _prepareCollection()
    {

        $collection = $this->_gridFactory->create()->getCollection();
        $this->setCollection($collection);
        parent::_prepareCollection();

        return $this;
    }
 
    protected function _prepareColumns()
    {

         $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

         $this->addColumn(
            'suppid',
            [
                'header' => __('Supplier ID'),
                'index' => 'suppid',                
                'class' => 'xxx'
            ]

        );

        $this->addColumn(
            'name',
            [
                'header' => __('Supplier Name'),
                'index' => 'name',                
                'class' => 'xxx'
            ]

        );

        $this->addColumn(
            'user',
            [
                'header' => __('Email Address'),
                'index' => 'user',                
                'class' => 'xxx'
            ]

        );


        $this->addColumn(
            'title',
            [
                'header' => __('Telephone'),
                'index' => 'title',                
                'class' => 'xxx'
            ]
        );


        $this->addColumn(
            'status',
            [
                'header' => __('Status'),
                'index' => 'status',
                'type' => 'options',                
                'options' => $this->_status->getOptionArray()
            ]
        );


        $this->addColumn(
            'createat',
            [
                'header' => __('Created At'),
                'index' => 'createat'
            ]
        );
 
 
        $this->addColumn(
            'edit',
            [
                'header' => __('Edit'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => 'suppliers/*/edit'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );

        $this->addColumn(
            'report',
            [
                'header' => __('Email'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Send'),
                        'url' => [
                            'base' => 'suppliers/*/sendmail'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );
 
        $this->addColumn(
            'delete',
            [
                'header' => __('Delete'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Delete'),
                        'url' => [
                            'base' => 'suppliers/*/delete'
                        ],
                        'field' => 'id'
                    ]
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action'
            ]
        );




        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
 
        return parent::_prepareColumns();
    }
 
   
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');
 
        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('suppliers/*/massDelete'),
                'confirm' => __('Are you sure?')
            ]
        );
 
        $statuses = $this->_status->toOptionArray();
 
        array_unshift($statuses, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('suppliers/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => __('Status'),
                        'values' => $statuses
                    ]
                ]
            ]
        );
 
 
        return $this;
    }
 
  
    public function getGridUrl()
    {
        return $this->getUrl('suppliers/*/grid', ['_current' => true]);
    }
 
    
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'suppliers/*/edit',
            ['id' => $row->getId()]
        );
    }
}