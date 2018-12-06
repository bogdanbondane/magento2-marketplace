<?php
namespace WorldnetTPS\Subscription\Api\Data;

interface GridInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const ENTITY_ID = 'entity_id';
    const MERCHANT_REF = 'merchant_ref';
    const TERMINAL_ID = 'terminal_id';

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
}