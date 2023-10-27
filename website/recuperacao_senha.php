<?php
    include("util.php");
    if (!isset($_GET['token'])) {
        header('Location: ./');
        die();
    }
    $token = $_GET['token'];
    $connection = connect();
    $select = $connection->prepare("SELECT usuario FROM tbl_recuperacao WHERE id_recuperacao = :token AND data_expiracao > NOW()");
    $select->execute(['token' => $token]);
    $result = $select->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500&display=swap" rel="stylesheet">         
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Nova senha | Mascotero</title>
</head>
    <body>
        <form name="formNovaSenha" method="post" action="./recuperacao_senha.php?token=<?php echo $token ?>" id="formlogin">
            <div id="logo-login">
                <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                Mascotero
            </div>
            <?php
                if ($result == NULL) {
                    echo "<div class='mensagem-erro'>Token inválido ou expirado!</div>";
                    die();
                }
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $id_usuario = $result['usuario'];
                    $password = $_POST['password'];
                    $update = $connection->prepare("UPDATE tbl_usuario SET senha = :password WHERE id_usuario = :id_usuario");
                    $update->execute(['password' => $password, 'id_usuario' => $id_usuario]);
                    $delete = $connection->prepare("DELETE FROM tbl_recuperacao WHERE id_recuperacao = :token");
                    $delete->execute(['token' => $token]);
                    header('Location: ./login.php');
                    die();
                }
            ?>
            <div class="label-input-login">
                <label for="password">Nova senha</label>
                <input type="password" name="password" id="password" placeholder="Nova senha" required>
            </div>
            <input type="submit" value="Enviar">
        </form>
    </body>
</html>