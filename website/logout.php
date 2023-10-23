<?php
    session_start();
    $sessID = session_id();
    session_destroy();
    session_set_cookie_params(3600);
    session_id($sessID);
    session_start();
    

    setcookie('loginCookie', '', time() - 3600);
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    header('Location: ' . $url);
?>