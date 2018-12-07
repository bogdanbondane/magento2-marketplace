<?php
namespace WorldnetTPS\Subscription\Block\Subscription;


class Index extends \Magento\Framework\View\Element\Template
{

    protected $_storeManager;

    protected $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        parent::__construct($context);

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    }
    protected function _prepareLayout()
    {

    }


    public function getSubscriptions()
    {
        $customerSession = $this->_objectManager->create('Magento\Customer\Model\SessionFactory')->create();

        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('worldnettps_subscription_customer_records'); //gives table name with prefix

        $result = array();
        if($customerSession->getCustomer()->getId()) {
            //Select Data from table
            $sql = "Select * FROM " . $tableName . " WHERE customer_id = " . $customerSession->getCustomer()->getId();
            $subscriptions = $connection->fetchAll($sql); // gives associated array, table fields as key in array.
            foreach ($subscriptions as $subscription) {
                $subscription['merchant_ref'] = '';
                $subscription['stored_subscription_merchant_ref'] = '';
                $subscription['secure_card_merchant_ref'] = '';

                array_push($result, $subscription);
            }
        }

        return $result;
    }
}