<?php

namespace App\tests;

use App\api\V1\Invoice\InvoiceFabric;
use App\api\V1\Models\CountryModel;
use App\api\V1\Models\OrderModel;
use App\Api\V1\Models\ProductModel;
use App\api\V1\Utils\ProductCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class SaleInvoiceTest
 * @package App\tests
 */
class SaleInvoiceTest extends TestCase
{

    protected $invoice;

    protected function setUp(): void
    {
        $countryModel = new CountryModel();
        $countryModel->setTax(20);

        $order = new OrderModel();
        $order->setCountry($countryModel);
        $order->setEmail("test@re.rt");
        $order->setInvoiceByEmail(0);

        $productCollection = new ProductCollection();
        $product_1 = new ProductModel('name1',2,5);
        $product_2 = new ProductModel('name2',1,10);
        $productCollection->addProduct($product_1);
        $productCollection->addProduct($product_2);

        $order->addProducts($productCollection);

        $tt = new InvoiceFabric();
        $invoice = $tt->create('Sale');
        $invoice->setOrder($order);
        $this->invoice = $invoice;
    }

    public function testCalculateUsualOrder()
    {

        $this->assertEquals(24,$this->invoice->calculate()->getTotalPrice());
        
    }

    public function testCalculateOrderWithOnBeforeModificator(){
        $this->invoice->setOnBeforePriceCalculate(function($or){
            foreach($or->getProducts()->iterate() as  &$product){
                $product->setPrice($product->getPrice()*2);
            }
        });
        $this->assertEquals(48, $this->invoice->calculate()->getTotalPrice());
    }

    public function testCalculateORderWithOnAfterModifier(){
        $this->invoice->setOnAfterPriceCalculate(function(&$finalPrice){
            $finalPrice = 10*$finalPrice;
        });

        $this->assertEquals(240, $this->invoice->calculate()->getTotalPrice());
    }

    public function testCalculationWithOnBeforeAndOnAfterModifier(){

        $this->invoice->setOnBeforePriceCalculate(function($or){
            foreach($or->getProducts()->iterate() as  &$product){
                $product->setPrice($product->getPrice()*2);
            }
        });

        $this->invoice->setOnAfterPriceCalculate(function(&$finalPrice){
            $finalPrice = 10*$finalPrice;
        });

        $this->assertEquals(480, $this->invoice->calculate()->getTotalPrice());

    }


}
