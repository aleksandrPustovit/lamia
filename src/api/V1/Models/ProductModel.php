<?php

namespace App\Api\V1\Models;


/**
 * Class ProductModel
 * @package App\Api\V1\Models
 */
class  ProductModel extends Model {

 		protected $tableName = 'products';
 		protected $name;
 		protected $price;
 		protected $quantity;
 		protected $tax;
 		protected $id;

     /**
      * @return mixed
      */
     public function getTax()
     {
         return $this->tax;
     }

     /**
      * @param mixed $tax
      */
     public function setTax($tax)
     {
         $this->tax = $tax;
     }

     /**
      * @return mixed
      */
     public function getId()
     {
         return $this->id;
     }

     /**
      * @param mixed $id
      */
     public function setId($id)
     {
         $this->id = $id;
     }
 		protected $orderId;

     /**
      * @param mixed $orderId
      */
     public function setOrderId($orderId)
     {
         $this->orderId = $orderId;
     }


     /**
      * ProductModel constructor.
      * @param $name
      * @param $quantity
      * @param $price
      */
     public function __construct($name, $quantity, $price){
            $this->name = $name;
            $this->price = $price;
            $this->quantity = $quantity;
 		 }


 		

	public function save(){

        $query = 'INSERT INTO ' .$this->tableName.
            ' (name, price, quantity,order_id)
            VALUES(:name, :price, :quantity, :order_id)';

        try{
            $req = $this->getConnection();

            $req->prepare($query)
                ->execute(array(
                    ':name' =>$this->name ,
                    ':price' => $this->price,
                    ':quantity' => $this->quantity,
                    ':order_id' => $this->orderId,
                ));
        }catch(\Exception $e){
            exit($e->getMessage());
        }

	}

     /**
      * @return string
      */
     public function getTablename() {
		return $this->tableName;
	}

     /**
      * @return mixed
      */
     public function getName()
     {
         return $this->name;
     }

     /**
      * @param mixed $name
      */
     public function setName($name)
     {
         $this->name = $name;
     }

     /**
      * @return mixed
      */
     public function getPrice()
     {
         return $this->price;
     }

     /**
      * @param mixed $price
      */
     public function setPrice($price)
     {
         $this->price = $price;
     }

     /**
      * @return mixed
      */
     public function getQuantity()
     {
         return $this->quantity;
     }

     /**
      * @param mixed $quantity
      */
     public function setQuantity($quantity)
     {
         $this->quantity = $quantity;
     }




 }