<?php


namespace App\api\V1\Invoice;


/**
 * Class InvoiceTemplate
 * Class for supporting different format types
 * @package App\api\V1\Invoice
 */
abstract class InvoiceTemplate
{

    protected $order;

    abstract public function print();

    /**
     * @param Invoice $order
     * @return mixed
     */
    abstract public function setOrder(Invoice $order);

}