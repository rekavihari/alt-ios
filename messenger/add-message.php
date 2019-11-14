<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

function cleanQuery($string)
{
  if(get_magic_quotes_gpc())  // prevents duplicate backslashes
  {
    $string = stripslashes($string);
  }
  if (phpversion() >= '4.3.0')
  {
    $string = mysql_real_escape_string($string);
  }
  else
  {
    $string = mysql_escape_string($string);
  }
  return $string;
}

Header('Content-type: application/json');
function setResponseErrorAndDie($errorstring)
{
	$items = array("result" => $errorstring, "status" => "error");
	echo json_encode($items);
	die();
}

$baseUrl="http://atleast.aut.bme.hu/ait-ios/messenger/";
$user="root";
$password="amorg1492";
$database="ios_course";
if (!mysql_connect('localhost',$user,$password))
  {
  setResponseErrorAndDie('Could not connect: ' . mysql_error());
  }
@mysql_select_db($database) or setResponseErrorAndDie("Unable to select database");


$post_body = file_get_contents('php://input');

$item = json_decode($post_body);

if (!isset($item->from_user) || strlen($item->from_user) == 0)
{
	setResponseErrorAndDie("from_user parameter is missing");
}
if (!isset($item->to_user) || strlen($item->to_user) == 0)
{
	setResponseErrorAndDie("to_user parameter is missing");
}
if (!isset($item->topic) || strlen($item->topic) == 0)
{
	setResponseErrorAndDie("topic parameter is missing");
}

$from_user = cleanQuery($item->from_user);
$to_user = cleanQuery($item->to_user);
$topic = cleanQuery($item->topic);
$message_content = "";
$imageuri = $baseUrl."noimage.png";

if (isset($item->content) && strlen($item->content) > 0)
{
	$message_content = $item->content;
}

if (isset($item->image) && strlen($item->image) > 0)
{
	$imageData = base64_decode($item->image);
	
	$fileName = "message_images/".md5($item->topic.$item->from_user.md5(microtime())).".jpeg";
	$fh = fopen($fileName, 'w') or setResponseErrorAndDie("Server error: failed to open file ".$fileName);
	fwrite($fh, $imageData);
	fclose($fh);
	
	$imageuri = $baseUrl.$fileName;
}

if (!isset($item->latitude))
{
	$lat = 0;
}
else
{
	$lat = cleanQuery($item->latitude);
}

if (!isset($item->longitude))
{
	$lon = 0;
}
else
{
	$lon = cleanQuery($item->longitude);
}

if ($lat < -90)
{
	$lat = -90;
}
if ($lat > 90)
{
	$lat = 90;
}
if ($lon < -180)
{
	$lon = -180;
}
if ($lon > 180)
{
	$lon = 180;
}

$query = "REPLACE INTO messages (from_user, to_user, topic, imageuri, content, latitude, longitude) VALUES ('".$from_user."', '".$to_user."', '".$topic."', '".$imageuri."', '".$message_content."', '".$lat."', '".$lon."')";

if (!mysql_query($query))
{
	setResponseErrorAndDie('Failed to execute replace query: ' . mysql_error());
}

$result = array("result" => "Message with topic ".$topic." has been posted to ".$to_user, "status" => "ok");
echo json_encode($result);