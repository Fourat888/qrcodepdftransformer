<?php
    session_start(); //start the session
    session_destroy(); //destroy the session
    header('Location: login.php'); //redirect to login page
?>