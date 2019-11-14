<?php

$restaurantName = $_GET["restaurant-name"];
if (!$restaurantName || strlen($restaurantName) == 0)
	die("Hiányzó \"restaurant-name\" paraméter. Kötelező megadni az étterem nevét!");

$restaurantDescription = $_GET["restaurant-description"];
if (!$restaurantDescription || strlen($restaurantDescription) == 0)
	$restaurantDescription = "Egy remek étterem";

$user="root";
$password="amorg1492";
$database="mobilszoftverek";
if (!mysql_connect('localhost',$user,$password))
{
  	die('Could not connect: ' . mysql_error());
}
@mysql_select_db($database) or setResponseErrorAndDie("Unable to select database");

$result = mysql_query("SELECT * FROM rg_restaurants");
$num_rows = mysql_num_rows($result);

$lat = 45 + 5.0*rand()/getrandmax();
$lon = 16 + 7.0*rand()/getrandmax();

$query = "REPLACE INTO rg_restaurants (name, description, latitude, longitude) VALUES ('".$restaurantName."', '".$restaurantDescription."', '".$lat."', '".$lon."')";
if (!mysql_query($query))
{	
	die('Failed to execute replace query: ' . mysql_error());
}
else
{
	echo "OK! Restaurant has been added. <br/>";
}
mysql_close();

// Put your device token here (without spaces):
$deviceToken = 'aec153f6b2ea39b6647f099a7b6c5ee990d9f18e9393f14a44b531b708d5a574';

// Put your private key's passphrase here:
$passphrase = 'restaurantguide';

// Put your alert message here:
$message = 'Új étterem felvéve: '.$restaurantName;

////////////////////////////////////////////////////////////////////////////////

$ctx = stream_context_create();
stream_context_set_option($ctx, 'ssl', 'local_cert', dirname(__FILE__) . '/' .'restaurantguide.pem');
stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

// Open a connection to the APNS server
$fp = stream_socket_client(
	'ssl://gateway.sandbox.push.apple.com:2195', $err,
	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

if (!$fp)
	exit("Failed to connect: $err $errstr" . PHP_EOL);

echo 'Connected to APNS' . PHP_EOL;

// Create the payload body
$body['aps'] = array(
	'alert' => $message,
	'sound' => 'default'	
	);
$body['restaurant-name'] = $restaurantName;

// Encode the payload as JSON
$payload = json_encode($body);

// Build the binary notification
$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

// Send it to the server
$result = fwrite($fp, $msg, strlen($msg));

if (!$result)
	echo 'Message not delivered' . PHP_EOL;
else
	echo 'Message successfully delivered' . PHP_EOL;

// Close the connection to the server
fclose($fp);
?>