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
  function startSession($time) {
    session_set_cookie_params(3600);

    session_start();

    $time == NULL ? $time = 3600 : '';

    if ((isset($_SESSION['lastActivity']) && (time() - $_SESSION['lastActivity'] > $time)) || $time != 3600) {
      $sessID = session_id();
      session_unset();
      session_destroy();
      ini_set('session.gc_maxlifetime', $time);
      session_set_cookie_params($time);
      session_id($sessID);
      session_start();
    }
    
    $_SESSION['lastActivity'] = time();

    if (!isset($_SESSION['user'])) {
      $_SESSION['user'] = array();
    }
    return session_id();
  }

  function swapSynonyms($word) {
    $synonyms = array (
      'informática' => 'info',
      'informatica' => 'info',
      'mecânica' => 'mec',
      'mecanica' => 'mec',
      'eletrônica' => 'eletro',
      'eletronica' => 'eletro'
    );
    return str_replace(array_keys($synonyms), $synonyms, $word);
  }

  function sendEmail($email, $subject, $body, $mail) {

    include_once('./PHPMailer/PHPMailer.php');
    include_once('./PHPMailer/Exception.php');
    include_once('./PHPMailer/SMTP.php');
    include('./hidden.php');

    $mail->isSMTP();
    $mail->setFrom('mascotero@outlook.com.br', 'Recuperação de Senha | Mascotero');
    $mail->Username = 'mascotero@outlook.com.br';
    $mail->Password = getPassword();
    $mail->Host = 'smtp.office365.com';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->addAddress($email);
    $mail->addEmbeddedImage('./imagens/Logo_Mascotero.png', 'logo');

    if ($mail->send()) {
      return true;
    }
    else {
      return false;
    }
  }

  function createPDF($html, $filename, $title, $download) {
    try {
      include_once('./FPDF/html_table.php');

      $pdf = new PDF();
      $pdf->AddPage();
      $pdf->SetFont('Times', 'B', 16);
      $pdf->Write(5, $title);
      $pdf->Ln();
      $pdf->SetFont('Times', '', 12);
      $pdf->WriteHTML($html);
      ob_end_clean();
      $download ? $pdf->Output($filename, 'D') : $pdf->Output($filename, 'I');
      return true;
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
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
        " . ($isAdmin ? "<li><a href=" . ($url == 'usuarios.php' ? '\'#\' id=\'nav-atual\'' : 'usuarios.php') . ">Usuários</a></li>" : '') . 
        ($isAdmin ? "<li><a href=" . ($url == 'relatorio.php' ? '\'#\' id=\'nav-atual\'' : 'relatorio.php') . ">Relatório</a></li>" : '') . "
      </ul>
    </nav>";
    $name = isset($_SESSION['user']['name']) ? explode(' ', $_SESSION['user']['name'], 2)[0] : 'visitante';
    $name = abreviarTexto($name,15);
    echo "
    <div id='container-usuario'>
      <span>Olá, </span>
      <span id='nome-usuario'> $name" . ($isAdmin ? ' - você é admin' : '') . "! </span>" .
      (isset($_SESSION['user']['id']) ? "<a href='./logout.php?url=$url'><img src='imagens/logout.svg' alt='Símbolo de logout'></a>" : "<a href='./login.php?url=$url'><img src='imagens/user_icon.svg' alt='Símbolo de cliente'></a>") .
      "<a href='./carrinho.php'><img src='imagens/carrinho.svg' alt='Carrinho de compras' id='simbolo-carrinho'></a>
  </div>";
  }
?>