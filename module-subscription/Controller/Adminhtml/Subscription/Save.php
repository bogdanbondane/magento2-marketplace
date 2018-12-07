<?php
namespace WorldnetTPS\Subscription\Controller\Adminhtml\Subscription;

use Magento\Backend\App\Action\Context;
use WorldnetTPS\Payment\Model\Api\XmlStoredSubscriptionRegRequest;
use WorldnetTPS\Payment\Model\Api\XmlStoredSubscriptionUpdRequest;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * @var \WorldnetTPS\Payment\Model\Api\XmlStoredSubscriptionRegRequest
     */
    protected $XmlStoredSubscriptionRegRequest;

    /**
     * @var \WorldnetTPS\Payment\Model\Api\XmlStoredSubscriptionUpdRequest
     */
    protected $XmlStoredSubscriptionUpdRequest;

    private $type = ['1' => 'AUTOMATIC', '2' => 'MANUAL', '3' => 'AUTOMATIC (WITHOUT AMOUNTS)'];
    private $periodType = ['2' => 'WEEKLY','3' => 'FORTNIGHTLY','4' => 'MONTHLY','5' => 'QUARTERLY','6' => 'YEARLY'];
    private $onUpdate = ['1' => 'CONTINUE', '2' => 'UPDATE'];
    private $onDelete = ['1' => 'CONTINUE', '2' => 'CANCEL'];

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

    public function getTerminalSettings($currency, &$terminalId, &$secret) {

        if ($currency == $this->getField('currency') && $this->getField('terminalid') && $this->getField('sharedsecret')) {
            $terminalId = $this->getField('terminalid');
            $secret = $this->getField('sharedsecret');
        } else if ($currency == $this->getField('currencytwo') && $this->getField('terminalidtwo') && $this->getField('sharedsecrettwo')) {
            $terminalId = $this->getField('terminalidtwo');
            $secret = $this->getField('sharedsecrettwo');
        } else if ($currency == $this->getField('currencythree') && $this->getField('terminalidthree') && $this->getField('sharedsecretthree')) {
            $terminalId = $this->getField('terminalidthree');
            $secret = $this->getField('sharedsecretthree');
        }

    }

    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        XmlStoredSubscriptionRegRequest $XmlStoredSubscriptionRegRequest,
        XmlStoredSubscriptionUpdRequest $XmlStoredSubscriptionUpdRequest
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->XmlStoredSubscriptionRegRequest = $XmlStoredSubscriptionRegRequest;
        $this->XmlStoredSubscriptionUpdRequest = $XmlStoredSubscriptionUpdRequest;
        parent::__construct($context);
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('worldnettps_subscription/subscription/addrow');
            return;
        }
        try {
            $rowData = $this->_objectManager->create('WorldnetTPS\Subscription\Model\Subscription');

            if (isset($data['entity_id'])) {
                $rowData->setEntityId($data['entity_id']);
            }

            $merchantRef = $data['merchant_ref'];
            $name = $data['name'];
            $description = $data['description'];
            $length = $data['period_length']?:0;
            $currency = $data['currency'];
            $recurringAmount = $data['type']==1?number_format($data['recurring_price'], 2, '.', ''):'0.00';
            $initialAmount = $data['type']!=3?number_format($data['setup_price'], 2, '.', ''):'0.00';
            $type = $this->type[$data['type']];
            $onUpdate = $this->onUpdate[$data['on_update']];
            $onDelete = $this->onDelete[$data['on_delete']];

            $this->getTerminalSettings($data['currency'],$terminalId, $secret);
            $serverUrl = $this->getServerUrl();

            $data['update_time'] = date('Y-m-d G:i:s');
            $data['terminal_id'] = $terminalId;

            if (isset($data['entity_id'])) {
                $this->XmlStoredSubscriptionUpdRequest->initXmlStoredSubscriptionUpdRequest($merchantRef, $terminalId, $name, $description, $length, $currency, $recurringAmount, $initialAmount, $type, $onUpdate, $onDelete, $secret);
                $response = $this->XmlStoredSubscriptionUpdRequest->ProcessRequestToGateway($serverUrl);
            } else {
                $periodType = $this->periodType[$data['period_type']];
                $data['created_at'] = date('Y-m-d G:i:s');
                $this->XmlStoredSubscriptionRegRequest->initXmlStoredSubscriptionRegRequest($merchantRef, $terminalId, $name, $description, $periodType, $length, $currency, $recurringAmount, $initialAmount, $type, $onUpdate, $onDelete, $secret);
                $response = $this->XmlStoredSubscriptionRegRequest->ProcessRequestToGateway($serverUrl);
            }


            $rowData->setData($data);

            if(!$response->IsError()) {
                $rowData->save();
                $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
            } else {
                $this->messageManager->addError(__($response->ErrorString()));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('worldnettps_subscription/subscription/index');
    }

    /**
     * Check Category Map permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WorldnetTPS_Auction::add_auction');
    }
}