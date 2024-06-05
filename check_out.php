<?php
require_once 'apiconfig.php';
require_once 'auth.php';
if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
}

$messaggio = '';

if (
    !empty($_POST["nome"]) && !empty($_POST["cognome"]) && !empty($_POST["email"]) && !empty($_POST["numero-carta"])
    && !empty($_POST["mese-scadenza"]) && !empty($_POST["anno-scadenza"]) && !empty($_POST["cvc-carta"])
) {
    $errore = array();
    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

    if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errore[] = "E-mail non valida";
    }

    if (!empty($_POST["nome"])) {
        $errore[] = "Il nome è obbligatorio";
    }

    if (!empty($_POST["cognome"])) {
        $errore[] = "Il cognome è obbligatorio";
    }

    if (!empty($_POST["numero-carta"]) || strlen($_POST["numero-carta"]) < 8) {
        $errore[] = "Numero di carta non valido";
    }

    if (!empty($_POST["mese-scadenza"]) || strlen($_POST["mese-scadenza"]) != 2) {
        $errore[] = "Mese di scadenza non valido";
    }

    if (!empty($_POST["anno-scadenza"]) || strlen($_POST["anno-scadenza"]) != 2) {
        $errore[] = "Anno di scadenza non valido";
    }

    if (!empty($_POST["cvc-carta"]) || strlen($_POST["cvc-carta"]) != 3) {
        $errore[] = "CVC non valido";
    }

    $responseData = stripe();

    if (isset($responseData['error'])) {
        $messaggio = 'Errore: ' . $responseData['error']['message'];
    } else {
        $messaggio = 'Pagamento completato con successo';
        $query = "SELECT id FROM carrelli WHERE id_utente = '$userid'";
        $res = mysqli_query($connection, $query) or die(mysqli_error($connection));

        $carrello_id = '';
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $carrello_id = $row['id'];
        }

        // Elimina prima i record nella tabella articoli_carrello
        $query = "DELETE FROM articoli_carrello WHERE id_carrello = '$carrello_id'";
        mysqli_query($connection, $query) or die(mysqli_error($connection));

        // Poi elimina il record nella tabella carrelli
        $query = "DELETE FROM carrelli WHERE id = '$carrello_id'";
        mysqli_query($connection, $query) or die(mysqli_error($connection));
    }
}

function stripe()
{
    $token = 'tok_visa';
    global $stripe;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/charges');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
        'amount' => ($_SESSION["totale"] * 100),
        'currency' => 'eur',
        'source' => $token,
        'description' => 'Acquisto da McDonalds',
    )));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $stripe["secret_key"],
        'Content-Type: application/x-www-form-urlencoded',
    ));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="check_out.css">
    <script src="checkout.js" defer></script>
    <title>CheckOut</title>
</head>

<body>
    <main>
        <div class="checkout-box">
            <?php if (!empty($messaggio)) {
                echo "<div class='container-pagamento'>
                <span> $messaggio</span>
                <a class='home' href='home.php'>home</a>
            </div>";
            }
            ?>
            <form name="pagamento" method="post">
                <div class="form-column left-column">
                    <div class="nome"><label for="nome"><input type="text" name="nome" placeholder="Nome"></label></div>
                    <div class="cognome"><label for="cognome"><input type="text" name="cognome" placeholder="Cognome"></label></div>
                    <div class="email"><label for="email"><input type="email" name="email" placeholder="E-mail"></label></div>
                    <div class="tel"><label for="tel"><input type="tel" name="tel" placeholder="Tel"></label></div>
                </div>
                <div class="form-column right-column">
                    <div class="numero-carta"><label for="numero-carta"><input type="text" name="numero-carta" placeholder="Numero Carta"></label></div>
                    <div class="mese-scadenza"><label for="mese-scadenza"><input type="text" name="mese-scadenza" placeholder="Mese scadenza (MM)"></label></div>
                    <div class="anno-scadenza"><label for="anno-scadenza"><input type="text" name="anno-scadenza" placeholder="Anno scadenza (YY)"></label></div>
                    <div class="cvc-carta"><label for="cvc-carta"><input type="password" name="cvc-carta" placeholder="CVC"></label></div>
                </div>
                <div class="submit"><label for="submit"><input type="submit" value="paga"></label></div>
            </form>
        </div>
    </main>
</body>

</html>