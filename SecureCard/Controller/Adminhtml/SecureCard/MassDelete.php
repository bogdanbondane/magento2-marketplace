<?php
namespace WorldnetTPS\SecureCard\Controller\Adminhtml\SecureCard;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use WorldnetTPS\SecureCard\Model\ResourceModel\SecureCard\CollectionDeleteFactory;
use WorldnetTPS\Payment\Model\Api\SecureCardRemovalRequest;

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

    /**
     * @param Context           $context
     * @param Filter            $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        SecureCardRemovalRequest $SecureCardRemovalRequest,
        Filter $filter,
        CollectionDeleteFactory $collectionFactory
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->SecureCardRemovalRequest = $SecureCardRemovalRequest;
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
            $cardReference = $auctionProduct->getCardReference();
            $terminalId = $auctionProduct->getTerminalId();
            $serverUrl = $this->getServerUrl();
            $this->getTerminalSettings($terminalId, $secret);
            $this->SecureCardRemovalRequest->initSecureCardRemovalRequest($merchantRef, $cardReference, $terminalId);
            $response = $this->SecureCardRemovalRequest->ProcessRequestToGateway($secret, $serverUrl);

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
        return $this->_authorization->isAllowed('WorldnetTPS_SecureCard::row_data_delete');
    }
}