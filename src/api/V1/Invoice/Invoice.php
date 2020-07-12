<?php


namespace App\api\V1\Invoice;


use App\api\V1\Models\OrderModel;


/**
 * Class Invoice
 *
 *  Class implements two modifiers for a price calculation
 *      1.setOnBeforePriceCalculate()
 *      2.setOnAfterPriceCalculate()
 * These methods have to be set before Calculate method.
 * @package App\api\V1\Invoice
 */
abstract class Invoice
{
    protected $order;
    private $onBeforePriceCalculate;
    private $onAfterPriceCalculate;
    private $totalPrice = 0;


    /**
     * @return OrderModel
     */
    public function getOrder(): OrderModel
    {
        return $this->order;
    }


    /** Force Extending class to define this method
     * @param OrderModel $order
     * @return mixed
     */
    abstract public function setOrder(OrderModel $order);

    /**
     * @return float
     */
    public function getTotalPrice():float
    {
        return $this->totalPrice;
    }

    /**
     * The function will be called before calculation method and allow user to modify Products;
     * As an argument you will get an Order model by reference
     *  Use iterate() method for a ProductCollection if you want to change values;
     *       $invoice->setOnBeforePriceCalculate(function($or){
     *          foreach($or->getProducts()->iterate() as  &$product){
     *              $product->setPrice($product->getPrice()*2);
     *          }
     *         });
     *
     * @param callable $onAfterPriceCalculate
     */
    public function setOnAfterPriceCalculate(Callable $onAfterPriceCalculate)
    {
        $this->onAfterPriceCalculate = $onAfterPriceCalculate;
    }

    /**The function will be called after calculation method and allow user to modify final price.
     * As an argument you will get an totalPrice
     *      $invoice->setOnAfterPriceCalculate(function(&$finalPrice){
     *          $finalPrice = 10*$finalPrice;
     *      });
     * @param callable $onBeforePriceCalculate
     */
    public function setOnBeforePriceCalculate(Callable $onBeforePriceCalculate)
    {
        $this->onBeforePriceCalculate = $onBeforePriceCalculate;
    }

    /**
     * This function wll return a copy of current object.
     * !!!!!! If there is any onBeforePriceCalculate OR onAfterPriceCalculate , modified products and properties will exist in returning object.!!!!!!
     * @return Invoice
     */
    public function calculate(): Invoice
    {

        $instance = clone $this;

        if ($this->onBeforePriceCalculate) {
            call_user_func_array($this->onBeforePriceCalculate, array(&$instance->order));
        }

        $instance->totalPrice = 0;
        $tax = $instance->order->getCountry()->getTax();


        foreach ($instance->order->getProducts()->iterate() as &$product) {
            $productTax = round(($product->getPrice() * $tax) / 100, 2);
            $instance->totalPrice += ($product->getPrice() + $productTax) * $product->getQuantity();
            $product->setTax($productTax);
        }

        if ($this->onAfterPriceCalculate) {
            ($this->onAfterPriceCalculate)($instance->totalPrice);
        }

        $instance->totalPrice = round($instance->totalPrice, 2);
        return $instance;
    }

    /**
     * @param InvoiceTemplate $template
     */
    public function print(InvoiceTemplate $template)
    {
        $template->setOrder($this);
        $template->print();
    }

    function __clone()
    {
        $this->order = clone $this->order;
    }

}