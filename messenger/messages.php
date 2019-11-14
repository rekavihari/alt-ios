<?php

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

header('Content-type: application/json');
header("Cache-Control: no-cache");
header("Pragma: no-cache");

function setResponseErrorAndDie($errorstring)
{
	$items = array("result" => $errorstring, "status" => "error");
  echo json_encode($items);
  die();
}

$user="root";
$password="amorg1492";
$database="ios_course";
if (!mysql_connect('localhost',$user,$password))
  {
  setResponseErrorAndDie('Could not connect: ' . mysql_error());
  }
@mysql_select_db($database) or setResponseErrorAndDie("Unable to select database");

$result = mysql_query("SELECT * FROM messages ORDER BY id DESC");

if (!$result)
{
	setResponseErrorAndDie('Failed to execute query: ' . mysql_error());
}

//$itemsXml = new SimpleXMLElement("<messages></messages>");

$items = array();

while($row = mysql_fetch_array($result))
{
  $item = array('from_user' => $row['from_user'],
    'to_user' => $row['to_user'],
    'topic' => $row['topic'],
    'content' => $row['content'],
    'imageurl' => $row['imageuri'],
    'latitude' => floatval($row['latitude']),
    'longitude' => floatval($row['longitude']));

  $items[] = $item;
}

echo json_encode($items);
