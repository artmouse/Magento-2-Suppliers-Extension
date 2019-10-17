<?php

namespace Informatics\Suppliers\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;

class Suppliers extends AbstractSource implements OptionSourceInterface
{

    /**
     * Suppliers factory
     *
     * @var \Informatics\Suppliers\Model\SuppliersFactory
     */
    protected $_suppliersFactory;

    /**
     * Construct
     *
     * @param \Informatics\Suppliers\Model\SuppliersFactory $suppliersFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Cache\Type\Config $configCacheType
     */
    public function __construct(
        \Informatics\Suppliers\Model\SuppliersFactory $suppliersFactory
    ) {
        $this->_suppliersFactory = $suppliersFactory;
    }

    /**
     * Get list of all available Suppliers
     *
     * @return array
     */
    public function getAllOptions()
    {
        $suppliersCollection = $this->_suppliersFactory->create()->getCollection();
        $this->_options = [['label'=>'Please select', 'value'=>'']];
        foreach($suppliersCollection as $suppliers)
        {
            $this->_options[] = ['label'=> $suppliers->getName(), 'value' => $suppliers->getId()]; //$suppliers->getSuppid()
        }
        return $this->_options;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }
}
