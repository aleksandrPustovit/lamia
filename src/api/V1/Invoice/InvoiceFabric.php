<?php


namespace App\api\V1\Invoice;


use phpDocumentor\Reflection\Types\String_;



/**
 * Class InvoiceFabric
 * Fabric for invoices.
 * If you want to make new invoice type You should make new namespace in invoice folder and make invoice method
 * That class should inherit App\api\V1\Invoice::Invoice abstract Class
 * @package App\api\V1\Invoice
 */
class InvoiceFabric
{

    /**
     * @param String $type
     * @return Invoice
     */
    public function create(String $type) :Invoice
    {
        $className = __NAMESPACE__. '\\'. $type.'\Invoice';
        if(class_exists($className,true)){
            return new $className();
        }


    }
}