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
    if (!isset($_SESSION['user'])) {
      $_SESSION['user'] = array();
    }
    return session_id();
  }
  function startCartSession() {
    session_start();
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
      // Se o usuário estiver logado, pega o carrinho do banco de dados
      if (isset($_SESSION['user']['id'])) {
        $connection = connect();
        $sessID = session_id();
        $select = $connection->prepare("SELECT id_compra FROM tbl_compra WHERE usuario = :id AND status = 'PENDENTE'");
        $select->execute(['id' => $_SESSION['user']['id']]);
        $result = $select->fetch(PDO::FETCH_ASSOC);
        // Se o usuário tiver uma compra pendente, pega os produtos dessa compra
        if ($result != NULL) {
          $select = $connection->prepare("SELECT id_produto, quantidade FROM tbl_compra_produto WHERE compra = :id");
          $select->execute(['id' => $result['id_compra']]);
          $result = $select->fetchAll(PDO::FETCH_ASSOC);
          foreach ($result as $row) {
            $_SESSION['cart'][$row['id_produto']] = $row['quantidade'];
          }
          // Se o usuário não tiver uma compra pendente, cria uma nova compra
        } else {
          $insert = $connection->prepare("INSERT INTO tbl_compra (usuario, status, sessao) VALUES (:id, 'PENDENTE', :sessao)");
          $insert->execute(['id' => $_SESSION['user']['id'], 'sessao' => $sessID]);
        }
      }
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
    echo "
    <div id='container-usuario'>
      <span>Olá, </span>
      <span id='nome-usuario'> $name - você é " . ($isAdmin ? 'admin' : 'cliente') . " </span>" .
      (isset($_SESSION['user']['id']) ? "<a href='./logout.php?url=$url'><img src='imagens/logout.svg' alt='Símbolo de logout'></a>" : "<a href='./login.php?url=$url'><img src='imagens/user_icon.svg' alt='Símbolo de cliente'></a>") .
      "<a href='./carrinho.php'><img src='imagens/carrinho.svg' alt='Carrinho de compras' id='simbolo-carrinho'></a>
  </div>";
  }
?>