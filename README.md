
# lamia
Installation :
1. Git clone
2. Run lamia.sql dump
3. composer update --no-scripts
4. Change Database settings in C:\OSPanel2\domains\lamia\src\api\V1\Utils\DB

Usage :


$products = array(
	array(
		'quantity' => 1,
		'name' => ' s',
		'price' => 0.5,
	),
	array(
		'quantity' => 2,
		'name' => 'Product 2',
		'price' => 10,
	),

);
$data = [
	'products' => $products,
	'country' => 'FI',
	'invoice_format' => 'HTML',
	'sent_email' => 1,
	'email' => 'ww@r.re',
];


$data = json_encode($data);
$ch = curl_init();

curl_setopt($ch, CURLOPT_HTTPHEADER, 
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data) ,
        ));


curl_setopt($ch, CURLOPT_URL,"http://lamia/api/v1/order");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
curl_close ($ch);

var_dump($server_output );