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
  function abreviarTexto($texto, $limite) {
    if (strlen($texto) > $limite-3) {
      return substr($texto, 0, $limite-3) . '...';
    }
    return $texto;
  }
  function startSession() {
    session_set_cookie_params(1209600);
    session_start();
    if (isset($_COOKIE['loginCookie']) && !isset($_SESSION['user']['id'])) {
        $connection = connect();
        $select = $connection->prepare("SELECT id_usuario, nome, email, senha, telefone, admin, cpf, data_cadastro, cep, endereco FROM tbl_usuario WHERE token = :token");
        $select->execute(['token' => $_COOKIE['loginCookie']]);
        $result = $select->fetch(PDO::FETCH_ASSOC);
        if ($result != NULL) {
            $_SESSION['user']['id'] = $result['id_usuario'];
            $_SESSION['user']['name'] = $result['nome'];
            $_SESSION['user']['email'] = $result['email'];
            $_SESSION['user']['password'] = $result['senha'];
            $_SESSION['user']['phone'] = $result['telefone'];
            $_SESSION['user']['isAdmin'] = $result['admin'];
            $_SESSION['user']['cpf'] = $result['cpf'];
            $_SESSION['user']['date'] = $result['data_cadastro'];
            !empty($result['cep']) ? $_SESSION['user']['cep'] = $result['cep'] : '';
            !empty($result['endereco']) ? $_SESSION['user']['address'] = $result['endereco'] : '';
        }
    }
    if (!isset($_SESSION['user'])) {
      $_SESSION['user'] = array();
    }
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
    $name = isset($_SESSION['user']['name']) ? explode(' ', $_SESSION['user']['name'], 2)[0] : 'visitante';
    $name = abreviarTexto($name,15);
    echo "
    <div id='container-usuario'>
      <span>Olá, </span>
      <span id='nome-usuario'> $name - você é " . ($isAdmin ? 'admin' : 'cliente') . " </span>" .
      (isset($_SESSION['user']['id']) ? "<a href='./logout.php?url=$url'><img src='imagens/logout.svg' alt='Símbolo de logout'></a>" : "<a href='./login.php?url=$url'><img src='imagens/user_icon.svg' alt='Símbolo de cliente'></a>") .
      "<a href='./carrinho.php'><img src='imagens/carrinho.svg' alt='Carrinho de compras' id='simbolo-carrinho'></a>
  </div>";
  }
?>