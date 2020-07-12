<?php

namespace App\Api\V1\Controllers;

use App\api\V1\Invoice\InvoiceFabric;
use App\Api\V1\Models\ProductModel;
use App\Api\V1\Models\OrderModel;
use App\Api\V1\Models\CountryModel;
use App\api\V1\Utils\ProductCollection;
use App\api\V1\Invoice\Sale\Templates\SalesInvoiceJSONTemplate;
use App\api\V1\Invoice\Sale\Templates\SalesInvoiceHTMLTemplate;

/**
 * Class Order
 * Api endpoind /api/v1/order
 * At this moment working only for POST request.
 * @package App\Api\V1\Controllers
 */
class Order extends Api
{
    public $apiName = 'order';

    private $productCollection;


    /**
     * For POST Request;
     * Works  with JSON format only
     */
    public function create()
    {


        $this->validateDataForOrder($this->requestJSONParams);
        $order = $this->makeOrder();

        $tt = new InvoiceFabric();
        $invoice = $tt->create('Sale');
        $invoice->setOrder($order);

//        $invoice->setOnBeforePriceCalculate(function($or){
//            foreach($or->getProducts()->iterate() as  &$product){
//                $product->setPrice($product->getPrice()*2);
//            }
//        });
//        $invoice->setOnAfterPriceCalculate(function(&$finalPrice){
//            $finalPrice = 10*$finalPrice;
//        });

        switch (strtoupper($this->requestJSONParams->invoice_format)) {
            case 'HTML':
                $template = new SalesInvoiceHTMLTemplate();
                break;

            default:
                $template = new SalesInvoiceJSONTemplate();
                break;
        }


        $finalOrder = $invoice->calculate();
        ob_start();
            $finalOrder->print($template);
        $template = ob_get_clean ();

        /*
         * @todo make Notification Gateway
         */

            if($this->requestJSONParams->sent_email){
//                try{
//                    $notifi = new NotificationGeteway('email')
//                        ->setTemplate('Ivoice template')
//                                ->setAttachment($this->requestJSONParams->email)
//                                ->setReceiver()
//                                ->send();
//
//                }catch(\Exception $e){
//                    exit($e->getMessage());
//                }

                echo 'Sending  email......';
            }else{
                echo $template;
            }




    }

    /**
     * @return OrderModel
     */
    private function makeOrder(): OrderModel
    {

        $order = new OrderModel();

        $countryModel = new CountryModel();
        $countryCodeID = $countryModel->findIdByCountryCode($this->requestJSONParams->country);


        $order->setCountry($countryModel->get($countryCodeID));
        $order->setEmail($this->requestJSONParams->email);
        $order->setInvoiceByEmail($this->requestJSONParams->sent_email);

        $this->productCollection = new ProductCollection();
        foreach ($this->requestJSONParams->products as $product) {
            $productObj = new ProductModel($product->name, $product->quantity, $product->price);
            $this->productCollection->addProduct($productObj);
        }

        $order->addProducts($this->productCollection);

        $order->save();
        return $order;

    }

    /**
     * @todo make validators as own class
     * @param $data
     */
    public function validateDataForOrder($data)
    {


        $this->validateContryCode($data->country);
        $this->validateProducts($data->products);
        $this->validateInvoiceFormat($data->invoice_format);
        $this->validateNotificationType($data->sent_email, $data->email);

    }

    /**
     * Validator for notifications properties
     * @todo make validors as own class depends from existing notifications.
     * @param $sentEmail
     * @param $email
     */
    public function validateNotificationType($sentEmail, $email)
    {
        if ($sentEmail && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            exit('Error in Notification');
        }
    }

    /**
     * Validator check alloud format types for invoices
     * @todo make validors as own class depends from existing invoices format
     * @param $format
     */
    public function validateInvoiceFormat($format)
    {
        if (!in_array(strtoupper($format), ['JSON', 'HTML']))
            exit('Error in invoice format');
    }

    /** Validator for country properties
     * @todo make validors as own class
     * @param $countrycode
     */
    public function validateContryCode($countrycode)
    {
        $countryModel = new CountryModel();

        if (!$countryModel->findIdByCountryCode($countrycode)) {
            echo "Exception Country";
            exit();
        }

    }

    /** Validator for products
     * @todo make validors as own class
     * @param $products
     */
    public function validateProducts($products)
    {
        $checkingParams = ['name', 'quantity', 'price'];

        if (is_array($products) && count($products) > 0) {
            foreach ($products as $product) {
                foreach ($checkingParams as $checkingParam) {
                    if (!isset($product->$checkingParam) || !trim($product->$checkingParam)) {
                        echo "ERROR IN PRODUCTs";
                        exit();
                    }
                }
            }
        } else {
            echo "ERROR IN PRODUCTs";
            exit();
        }

    }


    public function update()
    {
    }

    public function delete()
    {
    }

    public function index()
    {
    }


    public function view()
    {
    }

}