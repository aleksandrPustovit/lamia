<?php


namespace App\api\V1\Invoice\Sale\Templates;

use App\api\V1\Invoice\Invoice;
use App\api\V1\Invoice\InvoiceTemplate;


/**
 * Class SalesInvoiceHTMLTemplate
 * HTML template for Sale invoice
 * @package App\api\V1\Invoice\Sale\Templates
 */
class SalesInvoiceHTMLTemplate extends InvoiceTemplate
{

    public function print()
    {
        header("Content-Type: text/html; charset=UTF-8");
        ?>
            <!doctype html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport"
                      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Invoice</title>
            </head>
            <body>

            <h1>Invoice № <?=$this->order->getOrder()->getId()?></h1>

           <table>
               <tr>
                   <td>Name</td>
                   <td>Quantity</td>
                   <td>Price</td>
                   <td>Tax</td>
                   <td>Total</td>
               </tr>
                <?foreach($this->order->getOrder()->getProducts() as $product):?>
                    <tr>
                        <td><span><?=$product->getName()?></span></td>
                        <td><span><?=$product->getquantity()?></span></td>
                        <td><span><?=$product->getPrice()?></span></td>
                        <td><span><?=$product->getTax()?></span></td>
                        <td><span><?=round(($product->getTax() + $product->getPrice())*$product->getquantity(),2)?></span></td>
                    </tr>


                <?endforeach?>

               <tr>
                   <td>
                       Total:
                   </td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td><?=$this->order->getTotalPrice()?></td>
               </tr>
           </table>

            </body>
            </html>

        <?
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