<?php

namespace AndSystems\Lendmn\Factory;

use AndSystems\Lendmn\Model\InvoiceEvent;

class InvoiceEventFactory
{
    public static function createInvoiceEventFromArray($arr)
    {
        self::validateInvoiceEventArray($arr);

        $invoiceEvent = new InvoiceEvent();
        $invoiceEvent->setEvent($arr['eventType']);

        $invoiceDetail = InvoiceDetailFactory::createInvoiceDetailFromArray($arr['data']);
        $invoiceEvent->setInvoiceDetail($invoiceDetail);

        return $invoiceEvent;
    }

    private static function validateInvoiceEventArray($arr): void
    {
        Factory::checkMalformedParam($arr, 'data');
        Factory::checkMalformedParam($arr, 'eventType');
    }
}
