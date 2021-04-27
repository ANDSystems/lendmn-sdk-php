<?php

namespace  AndSystems\Lendmn\Model;

class InvoiceEvent
{
    const EVENT_PAID = 'invoice.paid';
    const EVENT_CANCELLED = 'invoice.cancelled';
    const EVENT_EXPIRED = 'invoice.expired';

    protected $event;

    /** @var InvoiceDetail invoiceDetail */
    protected $invoiceDetail;

    /** @return InvoiceDetail */
    public function getInvoiceDetail()
    {
        return $this->invoiceDetail;
    }

    /** @param InvoiceDetail */
    public function setInvoiceDetail($invoiceDetail)
    {
        $this->invoiceDetail = $invoiceDetail;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }
}
