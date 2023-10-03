<?php
  include("util.php");
  $sessID = startSession();

  if (isset($_SESSION['id_usuario'])) {
    header('Location: ./logout.php?url=login.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="icon" type="image/x-cion" href="/website/imagens/MC_Logo_Footer.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./js/script.js"></script>
    <title>Login</title>
</head>
<body>
    <div id="pai">
        <form name='formlogin' method='post' action='login.php' id="formlogin">
            <div id="logo-login">
                <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                Mascotero
            </div>
            <div id="label-input-login">
                <label for="email">Email</label>
                <input type='email' name='email' id="email" placeholder="Seu email aqui..." required>
            </div>
            <div id="label-input-login">
                <label for="password">Senha</label>
                <input type='password' name='password' id="password" placeholder="Sua senha aqui..." required>
            </div>
            <input type='submit' value='Enviar'>
            <a href='cadastro.php'>Não tem conta? Cadastre-se</a>

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $connection = connect();
    $select = $connection->prepare('select * from usuarios where email = :email and senha = :password');
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
    setcookie('loginCookie', $sessID, time() + 1209600);
    header('Location: ./');
  }
?>

          </form>
        </div>

</body>
</html>