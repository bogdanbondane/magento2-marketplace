<?php
namespace WorldnetTPS\Subscription\Controller\Adminhtml\Subscription;

use Magento\Backend\App\Action\Context;
use WorldnetTPS\Payment\Model\Api\XmlSubscriptionUpdRequest;

class CustomerSave extends \Magento\Backend\App\Action
{
    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \WorldnetTPS\Payment\Model\Api\XmlSubscriptionUpdRequest
     */
    protected $XmlSubscriptionUpdRequest;

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
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        XmlSubscriptionUpdRequest $XmlSubscriptionUpdRequest
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->XmlSubscriptionUpdRequest = $XmlSubscriptionUpdRequest;
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
            $this->_redirect('worldnettps_subscription/subscription/customeraddrow');
            return;
        }
        try {
            $rowData = $this->_objectManager->create('WorldnetTPS\Subscription\Model\SubscriptionCustomer');

            if (isset($data['entity_id'])) {
                $rowData->setEntityId($data['entity_id']);
            }

            $terminalId = $data['terminal_id'];
            $merchantRef = $data['merchant_ref'];
            $name = $data['name'];
            $description = $data['description'];
            $length = $data['period_length']?:0;
            $recurringAmount = number_format($data['recurring_price'], 2, '.', '');
            $secureCardMerchantRef = $data['secure_card_merchant_ref'];
            $startDate = $data['start_date'];

            $this->getTerminalSettings($terminalId, $secret);
            $serverUrl = $this->getServerUrl();

            $data['update_time'] = date('Y-m-d G:i:s');

            if (isset($data['entity_id'])) {
                $this->XmlSubscriptionUpdRequest->initXmlSubscriptionUpdRequest($merchantRef, $terminalId, $secureCardMerchantRef, $startDate, $secret);
                $this->XmlSubscriptionUpdRequest->SetSubName($name);
                $this->XmlSubscriptionUpdRequest->SetDescription($description);
                $this->XmlSubscriptionUpdRequest->SetLength($length);
                $this->XmlSubscriptionUpdRequest->SetRecurringAmount($recurringAmount);
                $response = $this->XmlSubscriptionUpdRequest->ProcessRequestToGateway($serverUrl);
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
        $this->_redirect('worldnettps_subscription/subscription/customer');
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