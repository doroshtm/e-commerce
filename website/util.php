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
    ini_set('session.gc_maxlifetime', 1209600);
    if (isset($_COOKIE['loginCookie'])) {
        session_id($_COOKIE['loginCookie']);
    }
    session_start();
    return session_id();
  }
  function header_pag ($isAdmin, $url) {
    echo "
    <div class='container-logo'> 
      <div id='imagem-logo'><img src='imagens/MC_Logo_Header.svg'> </div>
      <a href='./' style='all:unset;'>
        <div id='texto-logo'>Mascotero</div>
      </a> 
    </div>
    <nav id='nav-header'>
      <ul>
        <li><a href=" . ($url == 'index.php' ? '\'#\' id=\'nav-atual\'' : 'index.php') . ">Home</a></li>
        <li><a href=" . ($url == 'sobre.php' ? '\'#\' id=\'nav-atual\'' : 'sobre.php') . ">Sobre</a></li>
        <li><a href=" . ($url == 'produtos.php' ? '\'#\' id=\'nav-atual\'' : 'produtos.php') . ">Produtos</a></li>
        <li><a href=" . ($url == 'contato.php' ? '\'#\' id=\'nav-atual\'' : 'contato.php') . ">Contato</a></li>
        " . ($isAdmin ? "<li><a href=" . ($url == 'usuarios.php' ? '\'#\' id=\'nav-atual\'' : 'usuarios.php') . ">Usuários</a></li>" : '') . "
      </ul>
    </nav>";
    $name = isset($_SESSION['name']) ? explode(' ', $_SESSION['name'], 2)[0] : 'visitante';
    echo "
    <div id='container-usuario'>
      <span>Olá, </span>
      <span id='nome-usuario'> $name - você é " . ($isAdmin ? 'admin' : 'cliente') . " </span>" .
      (isset($_SESSION['id_usuario']) ? "<a href='./logout.php?url=$url'><img src='imagens/logout.svg' alt='Símbolo de logout'></a>" : "<a href='./login.php?url=$url'><img src='imagens/user_icon.svg' alt='Símbolo de cliente'></a>") .
      "<a href='./carrinho.php'><img src='imagens/carrinho.svg' alt='Carrinho de compras' id='simbolo-carrinho'></a>
  </div>";
  }
?>