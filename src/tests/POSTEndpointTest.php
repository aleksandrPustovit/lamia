<?php

namespace App\tests;

use EndPointTest;
use PHPUnit\Framework\TestCase;

class POSTEndpointTest extends TestCase
{

    protected $data;

    protected function setUp(): void
    {

        $products = array(
            array(
                'quantity' => 1,
                'name' => 'Product 1',
                'price' => 0.5,
            ),
            array(
                'quantity' => 2,
                'name' => 'Product 2',
                'price' => 10,
            ),
            array(
                'quantity' => 4,
                'name' => 'Product 3',
                'price' => 10,
            ),

        );
        $data = [
            'products' => $products,
            'country' => 'FI',
            'invoice_format' => 'JSON',
            'sent_email' => 0,
            'email' => 'ww@r.re',
        ];

        $this->data = json_encode($data);

    }

    public function testJsonResponse(){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($this->data) ,
        ));


        curl_setopt($ch, CURLOPT_URL,"http://lamia/api/v1/order");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$this->data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close ($ch);
        $responce = json_decode($server_output);


        $this->assertEquals('FI', $responce->Country);
        $this->assertEquals(75.02, $responce->Total_price);
        $this->assertEquals(24, $responce->Tax);

    }

}
