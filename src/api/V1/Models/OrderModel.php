<?php


namespace App\api\V1\Models;

use App\api\V1\Utils\ProductCollection;


/**
 * Class OrderModel
 * @package App\api\V1\Models
 */
class OrderModel extends Model
{
    protected $tableName = 'orders';

    private $countryModel;
    private $products;



    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function addProducts(ProductCollection $products)
    {
        $this->products = $products;
    }
    private $invoice_by_email;
    private $email;

    /**
     * @return mixed
     */
    public function getCountry():CountryModel
    {
        return $this->countryModel;
    }

    /**
     * @param $countrycode
     */
    public function setCountry(CountryModel $countrycode)
    {
        $this->countryModel = $countrycode;
    }



    /**
     * @return mixed
     */
    public function getInvoiceByEmail()
    {
        return $this->invoice_by_email;
    }

    /**
     * @param mixed $invoice_by_email
     */
    public function setInvoiceByEmail($invoice_by_email)
    {
        $this->invoice_by_email = $invoice_by_email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    public function save()
    {

        $query = 'INSERT INTO ' .$this->tableName.
            ' (email, country_id, invoice_by_email)
            VALUES(:email, :country_id, :invoice_by_email)';
        try{

            $req = $this->getConnection();

            $req->prepare($query)
            ->execute(array(
                ':email' =>$this->email ?? NULL,
                ':country_id' => $this->countryModel->getId(),
                ':invoice_by_email' => $this->invoice_by_email ?? 0,
            ));

            $orderId = $req->lastInsertId();

            $this->id = $orderId;


            foreach($this->products as $product){
                $product->setOrderId($orderId);
                $product->save();
            }



        }catch (\Exception $e){
            exit($e->getMessage());
        };

    }

    /**
     * @return string
     */
    public function getTablename()
    {
        return $this->tableName;
    }

    function __clone()
    {
        $this->products = clone $this->products;
    }
}