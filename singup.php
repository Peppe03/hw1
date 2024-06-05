<?php
    require_once 'auth.php';

    if (checkAuth()) {
        header("Location: home.php");
        exit;
    }   

    if(!empty($_POST["name"])&&!empty($_POST["surname"])&&!empty($_POST["email"])&&!empty($_POST["password"])){
        $errore=array();
        $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

        if(!filter_var($_POST["email"],FILTER_VALIDATE_EMAIL)){
            $errore[]="E-mail non valida";
        }else{
            $email=mysqli_real_escape_string($connection,strtolower($_POST["email"]));
            $res=mysqli_query($connection,"SELECT email FROM utenti WHERE email = '$email'");
            if(mysqli_num_rows($res)>0){
                $errore[]="E-mail già utilizzata";
            }
        }

        if(strlen($_POST["password"])<8){
            $error[]="Caratteri password insufficienti";
        }

        if(count($errore)==0){
            $name=mysqli_real_escape_string($connection,$_POST["name"]);
            $surname=mysqli_real_escape_string($connection,$_POST["surname"]);

            $password=mysqli_real_escape_string($connection,$_POST["password"]);
            $password=password_hash($password,PASSWORD_BCRYPT);

            $query = "INSERT INTO utenti(name, surname, email, password) VALUES('$name','$surname','$email','$password')";

            if(mysqli_query($connection,$query)){
                $_SESSION["session_email"] = $_POST["email"];
                $_SESSION["session_id"] = mysqli_insert_id($connection);
                mysqli_close($connection);
                header("Location: home.php");
                exit; 
            }else{
                $errore[]= "Errore di connessione";
            }
        }
        mysqli_close($connection);
    }else if(isset($_POST["email"])||isset($_POST["password"])){
        $errore=array("Riempi tutti i campi"); 
    }
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="singup.css">
    <title>SingUp</title>
</head>

<body>
    <main>
        <div class="singup-box">
            <form name='singup' method='post'>
                <h1>Sing Up</h1>
                <?php
                    if(isset($error)){
                        foreach($error as $err){
                            echo "<p class='errore'>".$error."</p>";
                        }
                    }
                ?>
                <div class="names">
                    <div class="name">
                        <label for="name"><input type="text" name="name" placeholder="Nome" <?php if(isset($_POST["name"])){echo "value=".$_POST["name"];}?> ></label>
                    </div>
                    <div class="surname">
                        <label for="surname"><input type="text" name="surname" placeholder="Cognome" <?php if(isset($_POST["surname"])){echo "value=".$_POST["surname"];}?>></label>
                    </div>
                </div>
                <div class="email">
                    <label for="email"><input type="email" name="email" placeholder="E-mail" <?php if(isset($_POST["email"])){echo "value=".$_POST["email"];}?>></label>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path fill="#000000"
                            d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z" />
                    </svg>
                </div>
                <div class="password">
                    <label for="password"><input type="password" name="password" placeholder="Password" <?php if(isset($_POST["password"])){echo "value=".$_POST["password"];}?>></label>
                    <svg xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                        <path fill="#000000"
                            d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z" />
                    </svg>
                </div>
                <div class="submit">
                    <label for="submit"><input type="submit" value="Registrati"></label>
                </div>
                <div class="login">
                    <p>Hai già un account?
                        <a href="login.php">Accedi</a>
                    </p>
                </div>
            </form>
        </div>
    </main>
</body>

</html>