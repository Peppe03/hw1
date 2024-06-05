<?php
include 'auth.php';
if (checkAuth()) {
    header('Location: home.php');
    exit;
}



if (!empty($_POST["email"]) && !empty($_POST["password"])) {

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

    $email = mysqli_real_escape_string($connection,$_POST["email"]);
    $query = "SELECT * FROM utenti WHERE email = '".$email."'";

    $res = mysqli_query($connection, $query) or die(mysqli_error($connection));

    if(mysqli_num_rows($res)>0){
        $entry=mysqli_fetch_assoc($res);
        if(password_verify($_POST["password"],$entry['password'])){
            $_SESSION["session_id"]=$entry['id'];
            $_SESSION["session_email"]=$entry['email'];
            header("Location: home.php");
            mysqli_free_result($res);
            mysqli_close($connection);
            exit;
        }
    }
    $error = "E-mail e/o password errati.";

}else if(isset($_POST["email"])||isset($_POST["password"])){
    $error = "Inserisci email e password.";
}

?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>

<body>
    <main>
        <div class="login-box">
            <form name='login' method='post'>
                <h1>Login</h1>
                <?php
                    if(isset($error)){
                        echo "<p class='errore'>$error</p>";
                    }
                ?>
                <div class="email">
                    <label for="email"><input type="email" name="email" placeholder="E-mail" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];}?> ></label>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path fill="#000000" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
                    </svg>
                </div>
                <div class="password">
                    <label for="password"><input type="password" name="password" placeholder="Password" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];}?> ></label>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path fill="#000000" d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z" />
                    </svg>
                </div>
                <div class="submit">
                    <label for="submit"><input type="submit" value="ACCEDI"></label>
                </div>
                <div class="register">
                    <p>Non hai un account?
                        <a href="singup.php">Registrati</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
</body>

</html>
