<?php
require_once 'auth.php';
require_once 'apiconfig.php';

if (!$userid = checkAuth()) {
    header("Location: login.php");
    exit;
}

?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="cart.js" defer></script>
    <link rel="stylesheet" href="cart.css">
    <title>Carrello</title>
</head>

<body>
    <main>
        <div class="box-carrello">
            <div class="griglia-carrello"></div>
            <div class="container-recap">
                <div class="recap"></div>
                <a href="check_out.php" class="compra"></a>
            </div>
        </div>
    </main>
</body>

</html>