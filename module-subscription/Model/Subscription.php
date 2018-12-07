<?php
namespace WorldnetTPS\Subscription\Model;

use WorldnetTPS\Subscription\Api\Data\GridInterface;

class Subscription extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'worldnettps_subscription_records';

    /**
     * @var string
     */
    protected $_cacheTag = 'worldnettps_subscription_records';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'worldnettps_subscription_records';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('WorldnetTPS\Subscription\Model\ResourceModel\Subscription');
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * Get MerchantRef.
     *
     * @return varchar
     */
    public function getMerchantRef()
    {
        return $this->getData(self::MERCHANT_REF);
    }

    /**
     * Set MerchantRef.
     */
    public function setMerchantRef($merchantRef)
    {
        return $this->setData(self::MERCHANT_REF, $merchantRef);
    }

    /**
     * Get TerminalId.
     *
     * @return varchar
     */
    public function getTerminalId()
    {
        return $this->getData(self::TERMINAL_ID);
    }

    /**
     * Set TerminalId.
     */
    public function setTerminalId($terminalId)
    {
        return $this->setData(self::TERMINAL_ID, $terminalId);
    }

}