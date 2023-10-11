<?php
    include("util.php");
    startSession();
    // if(!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin'] || $_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_GET['action'])) {
    //     header('Location: ./');
    // }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "" . $_GET['action'] . " | " . $_POST['delete'] ?> | Mascotero</title>
</head>
<body>
        <form method='post' action='./remocao_produto.php' id='formDelProduto'>
        <label for='confirm'>VOCÊ TEM CERTEZA?</label>
            <input type='checkbox' name='confirm' required>
            <input type='hidden' name='delete' value="<?php echo $_POST['delete'] ?>">
            <input type='submit' value='Excluir'>
            <input type='button' value='Cancelar' onclick='window.history.back()'>
        </form>
    </body>
</html>
<?php
    if(!isset($_POST['confirm']) || !$_POST['confirm']) {
        die();
    }
    if()
    $connection = connect();
    $delete_product = $connection->prepare('update tbl_produto set excluido = true where id_produto = ' . $_POST['delete']);
    $delete_product->execute();
    header('Location: ./produtos.php');
?>