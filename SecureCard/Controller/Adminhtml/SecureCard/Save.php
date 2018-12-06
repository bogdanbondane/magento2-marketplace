<?php
namespace WorldnetTPS\SecureCard\Controller\Adminhtml\SecureCard;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if (!$data) {
            $this->_redirect('worldnettps_securecard/securecard/addrow');
            return;
        }
        try {
            $rowData = $this->_objectManager->create('WorldnetTPS\SecureCard\Model\SecureCard');
            $rowData->setData($data);
            if (isset($data['id'])) {
                $rowData->setEntityId($data['id']);
            }
            $rowData->save();
            $this->messageManager->addSuccess(__('Row data has been successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('worldnettps_securecard/securecard/index');
    }

    /**
     * Check Category Map permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        //return $this->_authorization->isAllowed('WorldnetTPS_Auction::add_auction');
    }
}