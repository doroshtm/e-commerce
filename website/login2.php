<?php
  include("util.php");
  
  session_start();   

  // login que veio do form
  $email = $_POST['email'];
  $password = $_POST['password'];
  $isAdmin = false;

  if ($email<>'') {
      DefineCookie('loginCookie', $email, 60);
      $_SESSION['sessionConnected'] = login($email,$password,$isAdmin);
      $_SESSION['sessionAdmin']     = $isAdmin;
      echo $isAdmin ? "\tAdmin" : "\tUser";
  }
     
  // header('Location: index.php');
?> 