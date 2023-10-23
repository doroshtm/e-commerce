<?php
    include("util.php");
    startSession(3600);
    $method = $_SERVER['REQUEST_METHOD'];
    if($method != 'POST' && (!isset($_SESSION['user']['isAdmin']) || !$_SESSION['user']['isAdmin'] || !isset($_GET['action']) || !isset($_GET['id']))) {
        header('Location: ./');
    }
    $action = $method == 'POST' ? $_POST['action'] : $_GET['action'];
    $id = $method == 'POST' ? $_POST['id'] : $_GET['id'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo "" . $action . " produto | ID " . $id ?> | Mascotero</title>
    </head>
    <body>    
        <form method='post' action='./remocao_produto.php' id='formDelProduto'>
        <label for='confirm'>VOCÊ TEM CERTEZA?</label>
            <input type='checkbox' name='confirm' required><br><br>
            <label for='password'>Insira sua senha para confirmar</label>
            <input type='password' name='password' placeholder='Senha do administrador' required>
            <input type='hidden' name='id' value="<?php echo $id ?>">
            <input type='hidden' name='action' value="<?php echo $action ?>">
            <input type='submit' value=<?php echo $action ?>>
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
    $delete_product = $connection->prepare('UPDATE tbl_produto SET excluido = ' . ($action == 'Deletar' ? 'true' : 'false') . ' WHERE id_produto = ' . $id);
    $delete_product->execute();
    header('Location: ./produtos.php');
?>