<?php

namespace WorldnetTPS\SecureCard\Model\ResourceModel\SecureCard;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_init(
            'WorldnetTPS\SecureCard\Model\SecureCard',
            'WorldnetTPS\SecureCard\Model\ResourceModel\SecureCard'
        );
        parent::__construct(
            $entityFactory, $logger, $fetchStrategy, $eventManager, $connection,
            $resource
        );
        $this->storeManager = $storeManager;
    }
    protected function _initSelect()
    {
        parent::_initSelect();

        $this->getSelect()->joinLeft(
            ['secondTable' => $this->getTable('customer_entity')],
            'main_table.customer_id = secondTable.entity_id',
            ['customername' => "CONCAT(firstname, ' ', lastname, ' (', main_table.customer_id, ')')", 'firstname','middlename','lastname']
        );
    }

}