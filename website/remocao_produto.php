<?php
    include("util.php");
    startSession();
    if(!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin'] || $_SERVER['REQUEST_METHOD'] != 'POST') {
        header('Location: ./');
    }
    echo "
    <form method='post' action='./remocao_produto.php' id='formDelProduto'>
    <label for='confirm'>VOCÊ TEM CERTEZA?</label>
        <input type='checkbox' name='confirm' required>
        <input type='hidden' name='delete' value=" . $_POST['delete'] . ">
        <input type='submit' value='Excluir'>
        <input type='button' value='Cancelar' onclick='window.history.back()'>
    </form>";
    if(!isset($_POST['confirm'])) {
        die();
    }
    $connection = connect();
    $delete_product = $connection->prepare('delete from tbl_produto where id_produto = ' . $_POST['delete']);
    $delete_product->execute();
    header('Location: ./produtos.php');
?>