<?php
require_once 'auth.php';
if (!$userid = checkAuth()) exit;

header('Content-Type: application/json');

getCart();

function getCart()
{
    global $dbconfig, $userid;

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);

    if (!$connection) {
        echo json_encode(array('ok' => false, 'error' => 'Connection error'));
        exit;
    }

    $userid = mysqli_real_escape_string($connection, $userid);

    $query = "SELECT c.id AS id_carrello 
              FROM carrelli c 
              WHERE c.id_utente = '$userid'";
    $res = mysqli_query($connection, $query);

    if (!$res) {
        echo json_encode(array('ok' => false, 'error' => 'Query error: ' . mysqli_error($connection)));
        exit;
    }

    if (mysqli_num_rows($res) > 0) {
        $carrello = mysqli_fetch_assoc($res);
        $id_carrello = $carrello['id_carrello'];

        // Query per ottenere i dettagli del carrello
        $query = "SELECT p.id, p.nome, p.img, p.prezzo, ac.quantita, (p.prezzo * ac.quantita) as totale_prodotto 
                  FROM articoli_carrello ac 
                  JOIN prodotti p ON ac.id_prodotto = p.id 
                  WHERE ac.id_carrello = '$id_carrello'";
        $res = mysqli_query($connection, $query);

        if (!$res) {
            echo json_encode(array('ok' => false, 'error' => 'Query error: ' . mysqli_error($connection)));
            exit;
        }

        $prodotti = array();
        $totale_carrello = 0;
        $numero_totale_prodotti = 0;

        while ($row = mysqli_fetch_assoc($res)) {
            $prodotti[] = array(
                'id' => $row['id'],
                'nome' => $row['nome'],
                'img' => $row['img'],
                'prezzo' => $row['prezzo'],
                'quantita' => $row['quantita'],
                'totale_prodotto' => $row['totale_prodotto']
            );
            $totale_carrello += $row['totale_prodotto'];
            $numero_totale_prodotti += $row['quantita'];
        }

        $_SESSION["totale"] = number_format($totale_carrello, 2);

        $response = array(
            'ok' => true,
            'prodotti' => $prodotti,
            'totale' => number_format($totale_carrello, 2),
            'numero_totale_prodotti' => $numero_totale_prodotti
        );
    } else {
        $response = array(
            'ok' => true,
            'prodotti' => array(),
            'totale' => 0,
            'numero_totale_prodotti' => 0
        );
    }

    echo json_encode($response);
    mysqli_close($connection);
}
