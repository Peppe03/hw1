<?php
require_once 'dbconfig.php';

if (!isset($_GET["q"])) {
    echo "Non fare il furbo";
    exit;
}

header('Content-Type: application/json');

$connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

$email = mysqli_real_escape_string($connection, $_GET["q"]);

$query = "SELECT email FROM utenti WHERE email = '$email' ";

$res = mysqli_query($connection, $query) or die(mysqli_error($connection));

echo json_encode(array('exists' => mysqli_num_rows($res) > 0 ? true : false));

mysqli_close($connection);
