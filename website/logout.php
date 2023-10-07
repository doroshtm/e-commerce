<?php
    session_start();
    $_SESSION = array();
    session_destroy();

    setcookie('loginCookie', '', time() - 3600);
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    header('Location: ' . $url);
?>