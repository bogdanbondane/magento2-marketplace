<?php
namespace WorldnetTPS\SecureCard\Controller\SecureCard;

use WorldnetTPS\Payment\Model\Api\SecureCardRemovalRequest;

class Delete extends \Magento\Framework\App\Action\Action {

    protected $_storeManager;

    protected $_objectManager;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \WorldnetTPS\Payment\Model\Api\SecureCardRemovalRequest
     */
    protected $SecureCardRemovalRequest;

    public function getConfigData($field)
    {
        $path = 'payment/worldnettps_directpost/' . $field;
        return $this->_scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getField($field) {
        if ($this->getConfigData('mode') == 'LIVE')
            return  ($this->getConfigData($field));
        else
            return  ($this->getConfigData('test_'.$field));
    }

    public  function getServerUrl() {
        if ($this->getConfigData('mode') == 'LIVE')
            return $this->getConfigData('gatewayUrlXml');
        else
            return $this->getConfigData('testGatewayUrlXml');
    }

    public function getTerminalSettings($terminalId, &$secret) {

        if ($terminalId == $this->getField('terminalid') && $this->getField('sharedsecret')) {
            $secret = $this->getField('sharedsecret');
        } else if ($terminalId == $this->getField('terminalidtwo') && $this->getField('sharedsecrettwo')) {
            $secret = $this->getField('sharedsecrettwo');
        } else if ($terminalId == $this->getField('terminalidthree') && $this->getField('sharedsecretthree')) {
            $secret = $this->getField('sharedsecretthree');
        }

    }


    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        SecureCardRemovalRequest $SecureCardRemovalRequest,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->SecureCardRemovalRequest = $SecureCardRemovalRequest;

        $this->_storeManager = $storeManager;
        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        parent::__construct($context);
    }


    public function execute() {
        if($this->getRequest()->getPost('entity_id')) {
            $customerSession = $this->_objectManager->get('Magento\Customer\Model\Session');

            $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $tableName = $resource->getTableName('worldnettps_securecard_records'); //gives table name with prefix

            if ($customerSession->getCustomer()->getId()) {
                $sql = "Select * FROM " . $tableName . " WHERE entity_id = " . $this->getRequest()->getPost('entity_id') . " AND customer_id = " . $customerSession->getCustomer()->getId();
                $securecards = $connection->fetchAll($sql);
                $securecard = $securecards[0];

                $merchantRef = $securecard['merchant_ref'];
                $cardReference = $securecard['card_reference'];
                $terminalId = $securecard['terminal_id'];
                $serverUrl = $this->getServerUrl();
                $this->getTerminalSettings($terminalId, $secret);
                $this->SecureCardRemovalRequest->initSecureCardRemovalRequest($merchantRef, $cardReference, $terminalId);
                $response = $this->SecureCardRemovalRequest->ProcessRequestToGateway($secret, $serverUrl);

                if(!$response->IsError()) {
                    $sql = "DELETE FROM " . $tableName . " WHERE entity_id = " . $this->getRequest()->getPost('entity_id') . " AND customer_id = " . $customerSession->getCustomer()->getId();
                    $connection->query($sql);

                    return $this->getResponse()->setBody('{"success": true}');
                }
                else {
                    return $this->getResponse()->setBody('{"success": false, "errorString": "' . $merchantRef . ' ' . $response->ErrorString() . '"}');
                }

            }
        }
    }

}