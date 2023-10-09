<?php
  include("util.php");
  $sessID = startSession();

  if (isset($_SESSION['id_usuario'])) {
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
          <form name='formlogin' method='post' action='login.php?url=<?php echo isset($_GET['url']) ? $_GET['url'] : 'index.php'; ?>' id="formlogin">
              <div id="logo-login">
                  <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                  Mascotero
              </div>
              <div id="label-input-login">
                  <label for="email">Email</label>
                  <input type='email' name='email' id="email" placeholder="Seu email aqui..." required <?php echo isset($_COOKIE['email']) ? "value='{$_COOKIE['email']}'" : '' ?>>
              </div>
              <div id="label-input-login">
                  <label for="password">Senha</label>
                  <input type='password' name='password' id="password" placeholder="Sua senha aqui..." required>
                  <div id="container-lembrar-senha">
                    <input type='checkbox' name='lembrar-senha' id="lembrar-senha">
                    <label for="lembrar-senha">Mantenha-me conectado</label>
                  </div>
              </div>
              <input type='submit' value='Enviar'>
              <a href='cadastro.php?url=<?php echo isset($_GET['url']) ? $_GET['url'] : 'index.php'; ?>'>Não tem conta? Cadastre-se</a>
              <a href='esqueci.php'>Esqueceu a senha?</a>

  <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $email = $_POST['email'];
      $password = $_POST['password'];
      $connection = connect();
      $select = $connection->prepare('select * from tbl_usuario where email = :email and senha = :password');
      $select->execute(['email' => $email, 'password' => $password]);
      $result = $select->fetch(PDO::FETCH_ASSOC);
      if ($result == NULL) {
        echo "<div class='mensagem-erro'>E-mail ou senha incorretos!
        Tente novamente ou cadastre-se</div>";
        die();
      }
      $_SESSION['id_usuario'] = $result['id_usuario'];
      $_SESSION['name'] = $result['nome'];
      $_SESSION['email'] = $result['email'];
      $_SESSION['password'] = $result['senha'];
      $_SESSION['phone'] = $result['telefone'];
      $_SESSION['isAdmin'] = $result['admin'];
      $_SESSION['cpf'] = $result['cpf'];
      $_SESSION['date'] = $result['data_cadastro'];

      if ($_POST['lembrar-senha'] == 'on') {
        setcookie('loginCookie', $sessID, time() + 1209600);
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