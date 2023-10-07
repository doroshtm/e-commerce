<?php 
  function connect ($params = "") {
    if ($params == "") {
        $params="pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti24; 
                 user=projetoscti24; password=721369";
    }

    $varConn = new PDO($params);

    if (!$varConn) {
        echo "Não foi possivel conectar";
    } else { return $varConn; }
  }

  function startSession() {
    if (isset($_COOKIE['loginCookie'])) {
        session_id($_COOKIE['loginCookie']);
    }
    session_start();
    return session_id();
  }

  function login ($email, $password, &$isAdmin)  
  {
   $isAdmin = ($email == 'marcelo.peres@unesp.br' and 
                  $password == '12345');

   return true;
  }
  function header_pag ($isAdmin, $url) {
    $name = isset($_SESSION['name']) ? explode(' ', $_SESSION['name'], 2)[0] : 'visitante';
    echo "
    <div id='container-usuario'>
      <span>Olá, </span>
      <span id='nome-usuario'> $name - você é " . ($isAdmin ? 'admin' : 'cliente') . " </span>" .
      (isset($_SESSION['id_usuario']) ? "<a href='./logout.php?url=$url'><img src='imagens/logout.jpg' alt='Símbolo de logout' width=50 height=50></a>" : "<a href='./login.php?url=$url'><img src='imagens/user_icon.svg' alt='Foto do cliente'></a>") .
      "<a href='./carrinho.html'><img src='imagens/carrinho.svg' alt='Carrinho de compras'></a>
  </div>";
  }
?>