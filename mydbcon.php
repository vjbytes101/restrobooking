<?php
$ser = "localhost";
$user = "root";
$pass = "";
$db = "restbooking";

$con = mysql_connect($ser, $user, $pass, $db) or die("Connection Failed");
mysql_select_db('restbooking');
 ?>


