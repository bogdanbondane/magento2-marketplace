<?php
namespace WorldnetTPS\Subscription\Controller\Adminhtml\Subscription;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use WorldnetTPS\Subscription\Model\ResourceModel\Subscription\CollectionFactory;
use WorldnetTPS\Payment\Model\Api\XmlStoredSubscriptionDelRequest;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * Massactions filter.
     *
     * @var Filter
     */
    protected $_filter;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * @var \WorldnetTPS\Payment\Model\Api\XmlStoredSubscriptionDelRequest
     */
    protected $XmlStoredSubscriptionDelRequest;


    /**
     * Retrieve information from payment configuration
     *
     * @param string $field
     * @param int|string|null|\Magento\Store\Model\Store $storeId
     *
     * @return mixed
     */
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

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        XmlStoredSubscriptionDelRequest $XmlStoredSubscriptionDelRequest,
        Filter $filter,
        CollectionFactory $collectionFactory
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->XmlStoredSubscriptionDelRequest = $XmlStoredSubscriptionDelRequest;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        $recordDeleted = 0;
        foreach ($collection->getItems() as $auctionProduct) {
            $auctionProduct->setId($auctionProduct->getEntityId());

            $merchantRef = $auctionProduct->getMerchantRef();
            $terminalId = $auctionProduct->getTerminalId();
            $serverUrl = $this->getServerUrl();
            $this->getTerminalSettings($terminalId, $secret);
            $this->XmlStoredSubscriptionDelRequest->initXmlStoredSubscriptionDelRequest($merchantRef, $terminalId);
            $response = $this->XmlStoredSubscriptionDelRequest->ProcessRequestToGateway($secret, $serverUrl);

            if(!$response->IsError()) {
                $auctionProduct->delete();
                $recordDeleted++;
            } else {
                $this->messageManager->addError(
                    __('%1 %2 <br/>', $merchantRef, $response->ErrorString())
                );
            }
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $recordDeleted)
        );

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/index');
    }

    /**
     * Check delete Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('WorldnetTPS_Subscription::row_data_delete');
    }
}