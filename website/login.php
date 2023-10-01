<?php
  include("util.php");
  $sessID = startSession();

  if (isset($_SESSION['id_usuario'])) {
    header('Location: ./logout.php?url=login.php');
  }
?>

<html>
<header></header>
<body>
    <form name='formlogin' method='post' action='login.php'>
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

<?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $connection = connect();
    $select = $connection->prepare('select * from usuarios where email = :email and senha = :password');
    $select->execute(['email' => $email, 'password' => $password]);
    $result = $select->fetch(PDO::FETCH_ASSOC);
    if ($result == NULL) {
      echo "E-mail ou senha incorretos!
      Tente novamente ou cadastre-se";
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