<?php
    include("util.php");
    startSession(NULL);
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST' && (!isset($_SESSION['user']['isAdmin']) || !$_SESSION['user']['isAdmin'] || !isset($_GET['id']))) {
        header('Location: ./');
    }
    $id = $method == 'POST' ? $_POST['id'] : $_GET['id'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remover usuário | <?php echo $id ?> | Mascotero</title>
</head>
<body>
    <form method='post' action='./remocao_usuario.php' id='formDelUsuario'>
    <label for='confirm'>VOCÊ TEM CERTEZA? ESSA AÇÃO É IRREVERSÍVEL</label>
        <input type='checkbox' name='confirm' required><br><ber
        <label for='password'>Insira sua senha para confirmar</label>
        <input type='password' name='password' placeholder='Senha do administrador' required>
        <input type='hidden' name='id' value="<?php echo $id ?>">
        <input type='submit' value='Deletar'>
        <input type='button' value='Cancelar' onclick='window.history.back()'>
    </form>
</body>
</html>
<?php
    if($method != 'POST' || !isset($_POST['confirm']) || !$_POST['confirm'] || !isset($_POST['password'])) {
        die();
    } else if($_POST['password'] != $_SESSION['user']['password']) {
        echo "Senha incorreta!";
        die();
    }
    $connection = connect();
    $delete_user = $connection->prepare('DELETE FROM tbl_usuario WHERE id_usuario = :id');
    $delete_user->execute(['id' => $id]);
    header('Location: ./usuarios.php');
?>