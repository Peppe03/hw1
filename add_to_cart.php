<?php
require_once 'auth.php';
if (!$userid = checkAuth()) exit;

addToCart();

function addToCart()
{
    global $dbconfig, $userid;

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    if (!$connection) {
        echo json_encode(array('ok' => false, 'error' => 'Connection error'));
        exit;
    }

    $userid = mysqli_real_escape_string($connection, $userid);
    $id_prodotto = mysqli_real_escape_string($connection, $_POST['id']);
    $quantita = intval($_POST['quantita']);

    if ($quantita <= 0) {
        echo json_encode(array('ok' => false, 'error' => 'Quantità non valida'));
        exit;
    }

    $response = array('ok' => true);

    $query = "SELECT id FROM carrelli WHERE id_utente = '$userid'";
    $res = mysqli_query($connection, $query);

    if (!$res) {
        $response['ok'] = false;
        $response['error'] = 'Query error: ' . mysqli_error($connection);
        echo json_encode($response);
        exit;
    }

    if (mysqli_num_rows($res) > 0) {
        $carrello = mysqli_fetch_assoc($res);
        $id_carrello = $carrello['id'];
    } else {
        $query = "INSERT INTO carrelli(id_utente) VALUES('$userid')";
        if (!mysqli_query($connection, $query)) {
            $response['ok'] = false;
            $response['error'] = 'Insert carrello error: ' . mysqli_error($connection);
            echo json_encode($response);
            exit;
        }
        $id_carrello = mysqli_insert_id($connection);
    }

    $query = "SELECT quantita FROM articoli_carrello WHERE id_carrello = '$id_carrello' AND id_prodotto = '$id_prodotto'";
    $res = mysqli_query($connection, $query);

    if (!$res) {
        $response['ok'] = false;
        $response['error'] = 'Query error: ' . mysqli_error($connection);
        echo json_encode($response);
        exit;
    }

    if (mysqli_num_rows($res) > 0) {
        $articolo = mysqli_fetch_assoc($res);
        $quantita_attuale = $articolo['quantita'] + $quantita;
        $query = "UPDATE articoli_carrello SET quantita = '$quantita_attuale' WHERE id_carrello = '$id_carrello' AND id_prodotto = '$id_prodotto'";
    } else {
        $query = "INSERT INTO articoli_carrello(id_carrello, id_prodotto, quantita) VALUES('$id_carrello', '$id_prodotto', '$quantita')";
    }

    if (!mysqli_query($connection, $query)) {
        $response['ok'] = false;
        $response['error'] = 'Update/Insert articoli_carrello error: ' . mysqli_error($connection);
        echo json_encode($response);
        exit;
    }

    // Aggiornamento totale e numero di prodotti
    $query = "SELECT SUM(p.prezzo * ac.quantita) AS totale 
              FROM articoli_carrello ac 
              JOIN prodotti p ON ac.id_prodotto = p.id 
              WHERE ac.id_carrello = '$id_carrello'";
    $res = mysqli_query($connection, $query);

    if (!$res) {
        $response['ok'] = false;
        $response['error'] = 'Query error: ' . mysqli_error($connection);
        echo json_encode($response);
        exit;
    }

    $row = mysqli_fetch_assoc($res);
    $totale = $row['totale'];
    $response['totale'] = $totale;

    $query_num_prodotti = "SELECT COUNT(DISTINCT id_prodotto) AS num_prodotti 
                           FROM articoli_carrello 
                           WHERE id_carrello = '$id_carrello'";
    $res = mysqli_query($connection, $query_num_prodotti);

    if (!$res) {
        $response['ok'] = false;
        $response['error'] = 'Query error: ' . mysqli_error($connection);
        echo json_encode($response);
        exit;
    }

    $entry = mysqli_fetch_assoc($res);
    $num_prodotti = $entry['num_prodotti'];
    $response['num_prodotti'] = $num_prodotti;

    echo json_encode($response);
    mysqli_close($connection);
}
