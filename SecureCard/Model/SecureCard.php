<?php
namespace WorldnetTPS\SecureCard\Model;

use WorldnetTPS\SecureCard\Api\Data\GridInterface;

class SecureCard extends \Magento\Framework\Model\AbstractModel implements GridInterface
{
    /**
     * CMS page cache tag.
     */
    const CACHE_TAG = 'worldnettps_securecard_records';

    /**
     * @var string
     */
    protected $_cacheTag = 'worldnettps_securecard_records';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'worldnettps_securecard_records';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('WorldnetTPS\SecureCard\Model\ResourceModel\SecureCard');
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

    /**
     * Get CardExpiry.
     *
     * @return varchar
     */
    public function getCardExpiry()
    {
        return $this->getData(self::CARD_EXPIRY);
    }

    /**
     * Set CardExpiry.
     */
    public function setCardExpiry($cardExpiry)
    {
        return $this->setData(self::CARD_EXPIRY, $cardExpiry);
    }

    /**
     * Get CardType.
     *
     * @return varchar
     */
    public function getCardType()
    {
        return $this->getData(self::CARD_TYPE);
    }

    /**
     * Set CardType.
     */
    public function setCardType($cardType)
    {
        return $this->setData(self::CARD_TYPE, $cardType);
    }

    /**
     * Get CardHolderName.
     *
     * @return varchar
     */
    public function getCardHolderName()
    {
        return $this->getData(self::CARD_HOLDER_NAME);
    }

    /**
     * Set CardHolderName.
     */
    public function setCardHolderName($cardHolderName)
    {
        return $this->setData(self::CARD_HOLDER_NAME, $cardHolderName);
    }

    /**
     * Get CardReference.
     *
     * @return varchar
     */
    public function getCardReference()
    {
        return $this->getData(self::CARD_REFERENCE);
    }

    /**
     * Set CardReference.
     */
    public function setCardReference($cardReference)
    {
        return $this->setData(self::CARD_REFERENCE, $cardReference);
    }

    /**
     * Get CustomerId.
     *
     * @return varchar
     */
    public function getCustomerId()
    {
        return $this->getData(self::CUSTOMER_ID);
    }

    /**
     * Set CustomerId.
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get ObfuscatedCardNumber.
     *
     * @return varchar
     */
    public function getObfuscatedCardNumber()
    {
        return $this->getData(self::OBFUSCATED_CARD_NUMBER);
    }

    /**
     * Set ObfuscatedCardNumber.
     */
    public function setObfuscatedCardNumber($obfuscatedCardNumber)
    {
        return $this->setData(self::OBFUSCATED_CARD_NUMBER, $obfuscatedCardNumber);
    }

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set UpdateTime.
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::UPDATE_TIME, $updateTime);
    }

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get Firstname.
     *
     * @return varchar
     */
    public function getFirstname()
    {
        return $this->getData(self::FIRSTNAME);
    }

    /**
     * Set Firstname.
     */
    public function setFirstname($firstname)
    {
        return $this->setData(self::FIRSTNAME, $firstname);
    }
}