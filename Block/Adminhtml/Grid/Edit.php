<?php
 
namespace Informatics\Suppliers\Block\Adminhtml\Grid;
 
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    
    protected $_coreRegistry = null;
 
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
 
   
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'informatics_suppliers';
        $this->_controller = 'adminhtml_grid';
 
        parent::_construct();
 
        $this->buttonList->update('save', 'label', __('Save Supplier'));
        /*
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
        */

        $emailButtonProps = [
            'id' => 'add_new_gridmail',
            'label' => __('Send Email'),
            'class' => 'add',
            'button_class' => '',
            'onclick' => "setLocation('" . $this->_getEmailGenerate() . "')"
        ];

        $deliveredButtonProps = [
            'id' => 'add_new_grid',
            'label' => __('Mark as Delivered'),
            'class' => 'add',
            'button_class' => '',
            'onclick' => "setLocation('" . $this->_getMarkAsDelivered() . "')"
        ];
 
        $this->buttonList->update('delete', 'label', __('Delete'));

        $this->buttonList->add('add_new_button', $emailButtonProps);

        $this->buttonList->add('add_new', $deliveredButtonProps);

    }
 
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('informatics_form_data')->getId()) {
            return __("Edit Supplier Data '%1'", $this->escapeHtml($this->_coreRegistry->registry('informatics_form_data')->getTitle()));
        } else {
            return __('New Supplier');
        }
    }
 
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('suppliers/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '{{tab_id}}']);
    }
 
   
    protected function _prepareLayout()
    {
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'content');
                }
            };
        ";
        return parent::_prepareLayout();
    }

    protected function _getEmailGenerate()
    {
        return $this->getUrl(
            'suppliers/*/sendmail', 
            [
                '_current' => true, 
                'back' => 'edit', 
                'active_tab' => '{{tab_id}}'
            ]
        );
    }

    protected function _getMarkAsDelivered()
    {
        return $this->getUrl(
            'suppliers/*/delivered', 
            [
                '_current' => true, 
                'back' => 'edit', 
                'active_tab' => '{{tab_id}}'
            ]
        );
    }
 
}