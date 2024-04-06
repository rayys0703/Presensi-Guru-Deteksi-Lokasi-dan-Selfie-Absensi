<?php
require_once 'cfgcom.php';

$host = $config['host'];
$user = $config['user'];
$pass = $config['pass'];
$db = $config['db'];

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

require_once('database.php');
$database = new Database();
$conn = $database->__construct();

date_default_timezone_set('Asia/Jakarta');
?>