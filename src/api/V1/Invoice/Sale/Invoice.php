<?php


namespace App\api\V1\Invoice\Sale;
use App\api\V1\Invoice\Invoice as In;
use App\api\V1\Models\OrderModel;


/**
 * Class Invoice
 * Class for a Sales invoice type
 * @package App\api\V1\Invoice\Sale
 */
class Invoice extends In
{


    /**
     * @param OrderModel $order
     * @return mixed|void
     */
    public function setOrder(OrderModel $order)
    {
        $this->order = $order;
    }
}