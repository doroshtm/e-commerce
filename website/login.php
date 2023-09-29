<?php

  // se nao estiver conectado vai pedir o login
  if (isset($_SESSION['sessionConnected'])) {
      $sessionConnected = $_SESSION['sessionConnected'];
  } else { 
    $sessionConnected = false; 
  }

  // se sessao nao conectada ...
  if (!$sessionConnected) { 
     
     $loginCookie = '';

     // recupera o valor do cookie com o usuario    
     if (isset($_COOKIE['loginCookie'])) {
        $loginCookie = $_COOKIE['loginCookie']; 
     }
    }
?>

<html>
<header></header>
<body>
    <form name='formlogin' method='post' action='login2.php'>
        <table><tr>
          <td>E-mail<br>
          <input type='text' name='email' size=30 required></td>
          <td>Senha<br>
          <input type='password' name='password' size=8 required>
        </tr></table>
          <input type='submit' value='Enviar'></td>
      </form>
<a href='cadastro.php'>Não tem login? Cadastre-se</a>
</body>
</html>