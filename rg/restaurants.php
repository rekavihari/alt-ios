<?php
$user="root";
$password="amorg1492";
$database="mobilszoftverek";
if (!mysql_connect('localhost',$user,$password))
{
  	die('Could not connect: ' . mysql_error());
}
@mysql_select_db($database) or setResponseErrorAndDie("Unable to select database");

//$result = mysql_query("SELECT * FROM rg_restaurants");
//$restaurantCount = mysql_num_rows($result);

$restaurants = array();

$rs = mysql_query("SELECT name, description, latitude, longitude FROM rg_restaurants");
while($obj = mysql_fetch_object($rs)) 
{
	$rest = array('name' => $obj->name, 'description' => $obj->description, 'image'=> 'restaurant_icon', 'latitude' => $obj->latitude, 'longitude' => $obj->longitude);
	array_push($restaurants, $rest);
}
mysql_close();

echo json_encode($restaurants);

?>