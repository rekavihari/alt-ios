<?php
$user="root";
$password="amorg1492";
$database="ios_course";
$baseUrl="http://atleast.aut.bme.hu/ait-ios/messenger/";
mysql_set_charset('utf8');
if (!mysql_connect('localhost',$user,$password))
  {
  die('Could not connect: ' . mysql_error());
  }
@mysql_select_db($database) or die( "Unable to select database");

if (!mysql_query("DELETE FROM messages"))
{
	die('Failed to execute delete query: ' . mysql_error()."<br />");
}

$imageuri = $baseUrl."noimage.png";

$query = "REPLACE INTO messages (from_user, to_user, topic, content, imageuri, latitude, longitude) VALUES ('The Nameless One', 'Someone', 'Keep calm and...', '', '".$imageuri."', 47.562598, 19.054155)";
if (!mysql_query($query))
{
	die('Failed to execute query: ' . mysql_error()."<br />");
}


echo "Tables cleared";