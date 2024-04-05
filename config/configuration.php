<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS','@wasie123');
define('DB_NAME','rensdb');


$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if($conn->connect_error){

    die("Connection Error: ".$conn->connect_error);

}
