<?php
$user="root";
$password="amorg1492";
$database="ios_course";
if (!mysql_connect('localhost',$user,$password))
  {
  die('Could not connect: ' . mysql_error());
  }
@mysql_select_db($database) or die( "Unable to select database");
$query="CREATE TABLE messages (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, from_user varchar(100) NOT NULL, to_user varchar(100) NOT NULL, topic varchar(200) NOT NULL, imageuri varchar(300), content varchar(4096), latitude real, longitude real)";
if (mysql_query($query))
  {
  echo "OK \"messages\" created<br />";
  }
else
  {
  echo "ERROR creating messages: " . mysql_error()."<br />";
  }

mysql_close();
?>