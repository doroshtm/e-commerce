<?php

    
  if (isset($_SESSION['sessionConnected'])) {
      $sessionConnected = $_SESSION['sessionConnected'];
  } else { 
      $sessionConnected = false; 
  }

  // caso esteja logado ...
  if ( $sessionConnected ) {
      /*
      aqui vc coloca opcoes de 
      - fechar o carrinho e pagar
      - opcoes de perfil do usuario
        1. forma de pagamentos padrão por exemplo ...
        2. compras anteriores, etc
      */
      echo "<p align='right'><a href='logout.php'>Sair</a></p>";

      // caso seja administrador
      if ( $_SESSION['sessionAdmin'] ) {
         echo "<p align='right'>Bom dia, Administrador<br>";
         /*
          aqui vc colocar opcoes de administracao
          - cadastro de produtos
          - cadastro de usuarios 
          ...
         */   
      // caso seja um usuario comum
      } else {   
        echo "<p align='right'>Bom dia, Fulano<br></p>";
      }
  // caso nao esteja logado    
  } else {
      /*
       aqui vc pode
       - ver o carrinho
       - procurar produtos
      */
      echo "<p align='right'><a href='login.php'>Login</a></p>";
  }

  echo "<hr>";
?>