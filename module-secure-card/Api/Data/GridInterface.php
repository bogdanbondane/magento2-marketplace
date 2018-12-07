<?php
namespace WorldnetTPS\SecureCard\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const MERCHANT_REF = 'merchant_ref';
    const TERMINAL_ID = 'terminal_id';
    const CARD_EXPIRY = 'card_expiry';
    const CARD_TYPE = 'card_type';
    const CARD_HOLDER_NAME = 'card_holder_name';
    const CARD_REFERENCE = 'card_reference';
    const CUSTOMER_ID = 'customer_id';
    const OBFUSCATED_CARD_NUMBER = 'obfuscated_card_number';
    const UPDATE_TIME = 'update_time';
    const CREATED_AT = 'created_at';
    const FIRSTNAME = 'firstname';

    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getEntityId();

    /**
     * Set EntityId.
     */
    public function setEntityId($entityId);

    /**
     * Get MerchantRef.
     *
     * @return varchar
     */
    public function getMerchantRef();

    /**
     * Set MerchantRef.
     */
    public function setMerchantRef($merchantRef);

    /**
     * Get TerminalId.
     *
     * @return varchar
     */
    public function getTerminalId();

    /**
     * Set TerminalId.
     */
    public function setTerminalId($terminalId);

    /**
     * Get CardExpiry.
     *
     * @return varchar
     */
    public function getCardExpiry();

    /**
     * Set CardExpiry.
     */
    public function setCardExpiry($cardExpiry);

    /**
     * Get CardType.
     *
     * @return varchar
     */
    public function getCardType();

    /**
     * Set CardType.
     */
    public function setCardType($cardType);

    /**
     * Get CardHolderName.
     *
     * @return varchar
     */
    public function getCardHolderName();

    /**
     * Set CardHolderName.
     */
    public function setCardHolderName($cardHolderName);

    /**
     * Get CardReference.
     *
     * @return varchar
     */
    public function getCardReference();

    /**
     * Set CardReference.
     */
    public function setCardReference($cardReference);

    /**
     * Get CustomerId.
     *
     * @return varchar
     */
    public function getCustomerId();

    /**
     * Set CustomerId.
     */
    public function setCustomerId($customerId);

    /**
     * Get ObfuscatedCardNumber.
     *
     * @return varchar
     */
    public function getObfuscatedCardNumber();

    /**
     * Set ObfuscatedCardNumber.
     */
    public function setObfuscatedCardNumber($obfuscatedCardNumber);

    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getUpdateTime();

    /**
     * Set UpdateTime.
     */
    public function setUpdateTime($updateTime);

    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt();

    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt);



    /**
     * Get Firstname.
     *
     * @return varchar
     */
    public function getFirstname();

    /**
     * Set Firstname.
     */
    public function setFirstname($firstname);
}