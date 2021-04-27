<?php

namespace AndSystems\Lendmn\Model;

class Invoice
{
    /**
     * @var float
     * @var string
     * @var string
     * @var string
     */
    protected $amount;
    protected $invoiceNumber;
    protected $deepLink;
    protected $qrString;

    public function setAmount(float $amount)
    {
        $this->amount = $amount;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setDeepLink($deepLink)
    {
        $this->deepLink = $deepLink;
    }

    public function getDeepLink()
    {
        return $this->deepLink;
    }

    public function getQrLink()
    {
        return $this->getDeepLink();
    }

    public function setQrString($qrString)
    {
        $this->qrString = $qrString;
    }

    public function getQrString()
    {
        return $this->qrString;
    }

    public function __toString()
    {
        return $this->invoiceNumber;
    }
}
