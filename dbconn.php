<?php

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("HOSTNAME",$_ENV['DB_SERVER']);
define("USERNAME",$_ENV['DB_USERNAME']);
define("PASSWORD",$_ENV['DB_PASSWORD']);
define("DATABASE",$_ENV['DB_DATABASE']);

$conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
