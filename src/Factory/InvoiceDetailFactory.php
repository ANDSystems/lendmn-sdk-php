<?php

namespace AndSystems\Lendmn\Factory;

use AndSystems\Lendmn\Model\InvoiceDetail;

class InvoiceDetailFactory
{
    public static function createInvoiceDetailFromArray($arr)
    {
        self::validateInvoiceDetailArray($arr);

        $invoiceDetail = new InvoiceDetail();
        $invoiceDetail->setInvoiceNumber($arr['invoiceNumber']);
        $invoiceDetail->setStatus($arr['status']);
        $invoiceDetail->setAmount($arr['amount']);
        $invoiceDetail->setTrackingData($arr['trackingData']);
        $invoiceDetail->setDescription($arr['description']);
        $invoiceDetail->setCreatedAt($arr['createdAt']);
        $invoiceDetail->setExpiresAt($arr['expireDate']);

        if (isset($arr['paidDate']) && $arr['paidDate']) {
            $invoiceDetail->setPaidAt($arr['paidDate']);
        }

        return $invoiceDetail;
    }

    private static function validateInvoiceDetailArray($arr): void
    {
        Factory::checkKeysOfArray($arr, ['invoiceNumber', 'status', 'amount', 'description', 'trackingData', 'createdAt', 'expireDate']);
        Factory::checkMalformedParam($arr, 'paidDate', false);
    }
}
