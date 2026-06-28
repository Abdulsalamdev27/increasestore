<?php
define("DB_SERVER","localhost");
define("DB_USERNAME","root");
define("DB_PASSWORD","");
define("DB_DATABASE","increasesupplierstore");

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

//to know if the connection fails
if(!$conn)
    {
        die("Connection Failed: ".mysqli_connect_error());
    }



$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
    (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
) ? 'https' : 'http';


$host = $_SERVER['HTTP_HOST'];


define('BASE_PATH', dirname(__DIR__));


define(
    'BASE_URL',
    $protocol . '://' . $host . '/increasestore'
);


define(
    'API_BASE_URL',
    $protocol . '://' . $host . '/increasestore/e-api'
);



