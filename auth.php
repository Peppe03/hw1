<?php
    require_once 'dbconfig.php';
    
    session_start();

    function checkAuth(){
        if(isset($_SESSION["session_id"])){
            return $_SESSION["session_id"];
        }else
            return 0;
    }
?>