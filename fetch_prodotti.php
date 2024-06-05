<?php
    require_once 'auth.php';
    if(!$userid = checkAuth()) exit;

    header('Content-Type: application/json');

    $connection = mysqli_connect($dbconfig['host'],$dbconfig['user'],$dbconfig['password'],$dbconfig['name']);

    $userid = mysqli_real_escape_string($connection,$userid);

    $query = "SELECT * FROM prodotti";

    $res = mysqli_query($connection,$query) or die(mysqli_error($connection));

    $prodotti=array();
    while($entry=mysqli_fetch_assoc($res)){
        $prodotti[]=array('id'=>$entry['id'],
        'nome'=>$entry['nome'],
        'img'=>$entry['img'],
        'prezzo'=>$entry['prezzo']);
    }
    echo json_encode($prodotti);
    exit;
?>