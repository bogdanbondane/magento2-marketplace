<?php
namespace WorldnetTPS\SecureCard\Block\SecureCard;


class Index extends \Magento\Framework\View\Element\Template
{

    protected $_storeManager;

    protected $_objectManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    )
    {
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        parent::__construct($context);
    }
    protected function _prepareLayout()
    {

    }


    public function getSecureCards()
    {
        $customerSession = $this->_objectManager->create('Magento\Customer\Model\SessionFactory')->create();

        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('worldnettps_securecard_records'); //gives table name with prefix

        $result = array();
        if($customerSession->getCustomer()->getId()) {
            //Select Data from table
            $sql = "Select * FROM " . $tableName . " WHERE customer_id = " . $customerSession->getCustomer()->getId();
            $securecards = $connection->fetchAll($sql);
            foreach ($securecards as $securecard) {
                $securecard['merchant_ref'] = '';
                $securecard['card_reference'] = '';

                array_push($result, $securecard);
            }
        }

        return $result;
    }
}