<?php

require 'dbconfig.php';

header('Content-Type: application/json');

if (!empty($_GET["q"])) {

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

    $search = mysqli_real_escape_string($connection, $_GET["q"]);

    $query = "SELECT * FROM prodotti WHERE nome LIKE '%$search%'";

    $res = mysqli_query($connection, $query) or die($connection);

    $prodotti = array();

    if (mysqli_num_rows($res) > 0) {
        while ($entry = mysqli_fetch_assoc($res)) {
            $prodotti[] = array(
                'id' => $entry['id'],
                'nome' => $entry['nome'],
                'img' => $entry['img'],
                'prezzo' => $entry['prezzo']
            );
        }
        echo json_encode($prodotti);
    }
    exit;
} else {

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    $query = "SELECT * FROM prodotti";

    $res = mysqli_query($connection, $query) or die(mysqli_error($connection));

    $prodotti = array();
    while ($entry = mysqli_fetch_assoc($res)) {
        $prodotti[] = array(
            'id' => $entry['id'],
            'nome' => $entry['nome'],
            'img' => $entry['img'],
            'prezzo' => $entry['prezzo']
        );
    }
    echo json_encode($prodotti);
    exit;
}
