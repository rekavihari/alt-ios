<?php
$user="root";
$password="amorg1492";
$database="mobilszoftverek";
if (!mysql_connect('localhost',$user,$password))
  {
  die('Could not connect: ' . mysql_error());
  }
@mysql_select_db($database) or die( "Unable to select database");
$query="CREATE TABLE rg_restaurants (name varchar(100) NOT NULL,description varchar(250), latitude real,longitude real,imageuri varchar(300),PRIMARY KEY (name))";
if (mysql_query($query))
  {
  echo "OK \"rg_restaurants\" created<br />";
  }
else
  {
  echo "ERROR creating rg_restaurants: " . mysql_error()."<br />";
  }

mysql_close();
?>