<?php

namespace AndSystems\Lendmn\Factory;

use AndSystems\Lendmn\Model\Invoice;

class InvoiceFactory
{
    public static function createInvoiceFromArray($arr)
    {
        self::validateInvoiceArray($arr);

        $invoice = new Invoice();
        $invoice->setAmount($arr['amount']);
        $invoice->setInvoiceNumber($arr['invoiceNumber']);
        $invoice->setDeepLink($arr['qr_link']);
        $invoice->setQrString($arr['qr_string']);

        return $invoice;
    }

    private static function validateInvoiceArray($arr): void
    {
        Factory::checkMalformedParam($arr, 'amount');
        Factory::checkMalformedParam($arr, 'invoiceNumber');
        Factory::checkMalformedParam($arr, 'qr_link');
        Factory::checkMalformedParam($arr, 'qr_string');
    }
}
