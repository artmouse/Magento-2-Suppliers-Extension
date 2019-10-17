<?php

namespace Informatics\Suppliers\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{
    protected $eavSetup;

    public function __construct(EavSetup $eavSetup)
    {
        $this->eavSetup = $eavSetup;
    }
	
	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
		$setup->startSetup();

		if ($context->getVersion() && version_compare($context->getVersion(), '1.4.0') < 0) {

			$this->eavSetup->addAttribute(
				\Magento\Catalog\Model\Product::ENTITY,
	            'ift_supplier_code',
	            [
	                'type' => 'varchar',
	                'backend' => '',
	                'frontend' => '',
	                'label' => 'Supplier',
	                'input' => 'select',
	                'class' => '',
	                'source' => 'Informatics\Suppliers\Model\Attribute\Source\Suppliers',
	                'sort_order' => 50,
	                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
	                'visible' => true,
	                'required' => false,
	                'user_defined' => false,
	                'default' => 0,
	                'searchable' => false,
	                'filterable' => false,
	                'comparable' => false,
	                'visible_on_front' => false,
	                'used_in_product_listing' => true,
	                'unique' => false,
	                'apply_to' => 'simple,configurable,bundle,grouped'
	            ]
			);

		}
		/*
		if ($context->getVersion() && version_compare($context->getVersion(), '1.6.0') < 0) {

			if(!$setup->getAttributeId(\Magento\Catalog\Model\Product::ENTITY, 'ift_supplier_code')) { // check if it already exists

				$this->eavSetup->addAttribute(
					\Magento\Catalog\Model\Product::ENTITY,
		            'ift_supplier_code',
		            [
		                'type' => 'varchar',
		                'backend' => '',
		                'frontend' => '',
		                'label' => 'Supplier',
		                'input' => 'select',
		                'class' => '',
		                'source' => 'Informatics\Suppliers\Model\Attribute\Source\Suppliers',
		                'sort_order' => 50,
		                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
		                'visible' => true,
		                'required' => false,
		                'user_defined' => false,
		                'default' => 0,
		                'searchable' => false,
		                'filterable' => false,
		                'comparable' => false,
		                'visible_on_front' => false,
		                'used_in_product_listing' => true,
		                'unique' => false,
		                'apply_to' => 'simple,configurable,bundle,grouped'
		            ]
				);
			
			}

		}
		*/
		$setup->endSetup();
	}
}