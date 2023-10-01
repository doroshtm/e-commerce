<?php
    session_start();
    $_SESSION = array();
    session_destroy();

    setcookie('loginCookie', '', time() - 3600);
    $_GET['url'] = isset($_GET['url']) ? $_GET['url'] : 'index.php';
    header('Location: ' . $_GET['url']);
?>