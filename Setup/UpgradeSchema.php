<?php namespace Informatics\Suppliers\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements  UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup,
                            ModuleContextInterface $context){
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            // Get module table
            $tableName = $setup->getTable('informatics_suppliers');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'user' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Supplier Email Address',
                    ],
                    'status' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'comment' => 'Is Supplier Active',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }
        if (version_compare($context->getVersion(), '1.0.2') < 0) {

            // Get module table
            $tableName = $setup->getTable('informatics_suppliers');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'title' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Supplier Telephone',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }
        if (version_compare($context->getVersion(), '1.0.3') < 0) {

            // Get module table
            $tableName = $setup->getTable('informatics_suppliers');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'createat' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        null,
                        'nullable' => false,
                        'comment' => 'Created Date',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {

            $table = $setup->getConnection()
                ->newTable($setup->getTable('informatics_supplier_products'))
                ->addColumn('id',Table::TYPE_SMALLINT,null,['identity' => true, 'nullable' => false, 'primary' => true],'Supplier-Product ID')
                ->addColumn('supplierid',Table::TYPE_TEXT,55,[],'Supplier ID')
                ->addColumn('productid',Table::TYPE_TEXT,55,[],'Product ID')
                ->setComment('Informatics Supplier Products');

             // Get module table
            $tableName = $setup->getTable('informatics_supplier_products');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) != true) {

                $setup->getConnection()->createTable($table);

            }
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {

            // Get module table
            $tableName = $setup->getTable('informatics_suppliers');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'suppid' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => false,
                        'comment' => 'Supplier ID',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }

        if (version_compare($context->getVersion(), '1.0.7') < 0) {

            // Get module table
            $tableName = $setup->getTable('informatics_supplier_products');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'ordercount' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 55,
                        'nullable' => false,
                        'comment' => 'Ordered Count',
                    ],
                    'isdelivered' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'nullable' => false,
                        'comment' => 'Is Delivered',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->addColumn($tableName, $name, $definition);
                }

            }
        }

        if (version_compare($context->getVersion(), '1.0.8') < 0) {

            $table = $setup->getConnection()
                ->newTable($setup->getTable('informatics_supplier_product_logs'))
                ->addColumn('id',Table::TYPE_SMALLINT,null,['identity' => true, 'nullable' => false, 'primary' => true],'Delivery log ID')
                ->addColumn('supplierid',Table::TYPE_TEXT,55,[],'Supplier ID')
                ->addColumn('productid',Table::TYPE_TEXT,55,[],'Product ID')
                ->addColumn('productcount',Table::TYPE_TEXT,55,[],'Product Delivered Count')
                ->addColumn('deliverydate',Table::TYPE_TIMESTAMP,null,['nullable' => false],'Delivery Date')
                ->setComment('Informatics Supplier Product Logs');

             // Get module table
            $tableName = $setup->getTable('informatics_supplier_product_logs');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) != true) {

                $setup->getConnection()->createTable($table);

            }
        }

        if (version_compare($context->getVersion(), '1.0.9') < 0) {

            // Get module table
            $tableName = $setup->getTable('informatics_supplier_products');

            // Check if the table already exists
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                // Declare data
                $columns = [
                    'ordercount' => [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 55,
                        'nullable' => false,
                        'default' => 0,
                        'comment' => 'Current Order Count',
                    ],
                ];
                
                $connection = $setup->getConnection();
                foreach ($columns as $name => $definition) {
                    $connection->changeColumn($tableName, $name, $name, $definition);
                }

            }
        }

        $setup->endSetup();
    }
}