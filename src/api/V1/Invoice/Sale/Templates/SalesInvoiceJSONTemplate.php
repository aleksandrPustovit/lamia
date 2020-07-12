<?php


namespace App\api\V1\Invoice\Sale\Templates;

use App\api\V1\Invoice\Invoice;
use App\api\V1\Invoice\InvoiceTemplate;
use App\api\V1\Models\OrderModel;


/**
 * Class SalesInvoiceJSONTemplate
 * JSON format for Sale invoice
 * @package App\api\V1\Invoice\Sale\Templates
 */
class SalesInvoiceJSONTemplate extends InvoiceTemplate
{

    public function print()
    {

        $countryInfo = $this->order->getOrder()->getCountry();
        $products = [];
        foreach ($this->order->getOrder()->getProducts() as $product){
            $tmp['name'] = $product->getName();
            $tmp['quantity'] = $product->getquantity();
            $tmp['price'] = $product->getPrice();
            $tmp['tax'] = $product->getTax();
            $tmp['final_price'] = round(($tmp['tax'] + $tmp['price'])*$tmp['quantity'],2);
            $products[] = $tmp;
        }
        $order = array(
            'Country' => $countryInfo->getCountryCode(),
            'Number' =>$this->order->getOrder()->getId(),
            'Tax' => $countryInfo->getTax(),
            'Total_price' => $this->order->getTotalPrice(),
            'Products' => $products,
        );
        echo json_encode($order);


    }

    /**
     * @param Invoice $order
     * @return mixed|void
     */
    public function setOrder(Invoice $order)
    {
        $this->order = $order;
    }
}