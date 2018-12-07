<?php

namespace WorldnetTPS\Subscription\Model\ResourceModel\SubscriptionCustomer;
 
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class CollectionDelete extends AbstractCollection
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
            'WorldnetTPS\Subscription\Model\SubscriptionCustomer',
            'WorldnetTPS\Subscription\Model\ResourceModel\SubscriptionCustomer'
        );
        parent::__construct(
            $entityFactory, $logger, $fetchStrategy, $eventManager, $connection,
            $resource
        );
        $this->storeManager = $storeManager;
    }

}