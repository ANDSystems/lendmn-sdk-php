<?php

namespace  AndSystems\Lendmn\Model;

use AndSystems\Lendmn\Factory\Factory;

class InvoiceDetail
{
    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_EXPIRED = 3;

    protected $invoiceNumber;
    protected $status;
    protected $amount;
    protected $trackingData;
    protected $description;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime */
    protected $expiresAt;

    /** @var \DateTime */
    protected $paidAt;

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getTrackingData()
    {
        return $this->trackingData;
    }

    public function setTrackingData($trackingData)
    {
        $this->trackingData = $trackingData;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /** @param \DateTime|string $expiresAt */
    public function setCreatedAt($createdAt)
    {
        $createdAt = Factory::sanitizeStringToDateTime($createdAt, 'createdAt');
        $this->createdAt = $createdAt;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /** @param \DateTime|string $expiresAt */
    public function setExpiresAt($expiresAt)
    {
        /** @var \DateTime $expiresAt */
        $expiresAt = Factory::sanitizeStringToDateTime($expiresAt, 'expiresAt');

        $now = new \DateTime('now');

        if (self::STATUS_PENDING == $this->getStatus() && $expiresAt < $now) {
            $this->setStatus(self::STATUS_EXPIRED);
        }
        $this->expiresAt = $expiresAt;
    }

    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /** @param \DateTime|string $expiresAt */
    public function setPaidAt($paidAt)
    {
        $paidAt = Factory::sanitizeStringToDateTime($paidAt, 'paidAt');

        return $this->paidAt = $paidAt;
    }

    public function toArray()
    {
        return [
            'invoiceNumber' => $this->invoiceNumber,
            'status' => $this->status,
            'description' => $this->description,
            'amount' => $this->amount,
            'trackingData' => $this->trackingData,
            'createdAt' => $this->createdAt->format(DATE_ISO8601),
            'expiresAt' => $this->expiresAt->format(DATE_ISO8601),
            'paidAt' => $this->paidAt ? $this->paidAt->format(DATE_ISO8601) : null,
        ];
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}
