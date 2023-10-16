<?php
  include("util.php");
  $sessID = startSession();

  if (isset($_SESSION['user']['id'])) {
    header('Location: ./logout.php?url=login.php');
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
  <head>
      <meta charset="UTF-8">
      <link rel="stylesheet" href="styles_header_footer.css">
      <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login | Mascotero</title>
  </head>
  <body>
      <div id="pai">
          <form name="formlogin" method="post" action="login.php?url=<?php echo isset($_GET['url']) ? $_GET['url'] : 'index.php'; ?>" id="formlogin">
              <div id="logo-login">
                  <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                  Mascotero
              </div>
              <div class="label-input-login">
                  <label for="email">Email</label>
                  <input type="email" name="email" id="email" placeholder="Seu email aqui..." required <?php echo isset($_COOKIE['email']) ? "value='{$_COOKIE['email']}'" : '' ?>>
              </div>
              <div class="label-input-login">
                  <label for="password">Senha</label>
                  <input type="password" name="password" id="password" placeholder="Sua senha aqui..." required>
              </div>
              <input type="submit" value="Entrar">
              <div id="container-lembrar-senha">
                    <input type="checkbox" name="rememberme" id="lembrar-senha">
                    <label for="rememberme">Mantenha-me conectado</label>
              </div>
              <a href="cadastro.php<?php echo isset($_GET['url']) ? '?url=' . $_GET['url'] : '' ?>" id="cadastre-se">Não tem conta? Cadastre-se</a>
              <a href="esqueci.php">Esqueceu a senha?</a>

  <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $connection = connect();
      $select = $connection->prepare("SELECT id_usuario, nome, email, senha, telefone, admin, cpf, data_cadastro, cep, endereco FROM tbl_usuario WHERE email = :email AND senha = :password");
      $select->execute(['email' => $email, 'password' => $password]);
      $result = $select->fetch(PDO::FETCH_ASSOC);
      if ($result == NULL) {
        echo "<div class='mensagem-erro'>E-mail ou senha incorretos!
        Tente novamente ou cadastre-se</div>";
        die();
      }
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

      if ($_POST['rememberme'] == 'on') {
        $tokenREMEMBER = generateToken();
        setcookie('loginCookie', $tokenREMEMBER, time() + 1209600);
        insertToken($tokenREMEMBER, $result['id_usuario']);
      }
      setcookie('email', $email, time() + 1209600);
      $url = isset($_GET['url']) ? $_GET['url'] : '';
      header('Location: ' . $url);
    }
  ?>

            </form>
          </div>

  </body>
</html>