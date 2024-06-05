<?php
require_once 'auth.php';
if (!$userid = checkAuth()) exit;

removeFromCart();

function removeFromCart()
{
    global $dbconfig, $userid;

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    if (!$connection) {
        echo json_encode(array('ok' => false, 'error' => 'Connection error'));
        exit;
    }

    $userid = mysqli_real_escape_string($connection, $userid);
    $id_prodotto = mysqli_real_escape_string($connection, $_POST['id']);

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
        $response['ok'] = false;
        $response['error'] = 'Carrello non trovato';
        echo json_encode($response);
        exit;
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
        $quantita_attuale = $articolo['quantita'];

        if ($quantita_attuale > 1) {
            $quantita_attuale--;
            $query = "UPDATE articoli_carrello SET quantita = '$quantita_attuale' WHERE id_carrello = '$id_carrello' AND id_prodotto = '$id_prodotto'";
        } else {
            $query = "DELETE FROM articoli_carrello WHERE id_carrello = '$id_carrello' AND id_prodotto = '$id_prodotto'";
        }

        if (!mysqli_query($connection, $query)) {
            $response['ok'] = false;
            $response['error'] = 'Update/Delete articoli_carrello error: ' . mysqli_error($connection);
            echo json_encode($response);
            exit;
        }
    } else {
        $response['ok'] = false;
        $response['error'] = 'Articolo non trovato nel carrello';
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
