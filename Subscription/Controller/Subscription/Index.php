<?php
namespace WorldnetTPS\Subscription\Controller\Subscription;

class Index extends \Magento\Framework\App\Action\Action {

    protected $_pageConfig;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Page\Config $pageConfig
    ) {
        parent::__construct($context);
        $this->_pageConfig = $pageConfig;
    }


    public function execute() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $customerSession = $objectManager->get('\Magento\Customer\Model\Session');
        $urlInterface = $objectManager->get('\Magento\Framework\UrlInterface');

        if(!$customerSession->isLoggedIn()) {
            $customerSession->setAfterAuthUrl($urlInterface->getCurrentUrl());
            $customerSession->authenticate();
        }

        $this->_view->loadLayout();
        $this->_pageConfig->getTitle()->set(__('WorldNetTPS Subscriptions'));

        $this->_view->renderLayout();
    }

}