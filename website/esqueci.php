<?php
    include("util.php");
    include_once('./PHPMailer/PHPMailer.php');
    $mail = new PHPMailer\PHPMailer\PHPMailer();
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
        <title>Esqueci a senha | Mascotero</title>
    </head>
    <body>
        <div id="pai">
            <form name="formEsqueci" method="post" action="esqueci.php" id="formlogin">
                <div id="logo-login">
                    <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                    Mascotero
                </div>
                <div class="label-input-login">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Seu email aqui..." required <?php echo isset($_COOKIE['email']) ? "value='{$_COOKIE['email']}'" : '' ?>>
                </div>
                <input type="submit" value="Enviar">
                <a href = "login.php<?php echo isset($_GET['url']) ? '?url=' . $_GET['url'] : '' ?>" id="voltar">Voltar</a>
            <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $email = $_POST['email'];
                    $connection = connect();
                    $select = $connection->prepare("SELECT nome, id_usuario FROM tbl_usuario WHERE email = :email");
                    $select->execute(['email' => $email]);
                    $result = $select->fetch(PDO::FETCH_ASSOC);
                    if ($result == NULL) {
                        echo "<div class='mensagem-erro'>E-mail não cadastrado!</div>";
                        die();
                    }
                    $selectRecuperacao = $connection->prepare("SELECT id_recuperacao FROM tbl_recuperacao WHERE usuario = :id_usuario AND data_expiracao > NOW()");
                    $selectRecuperacao->execute(['id_usuario' => $result['id_usuario']]);
                    $resultRecuperacao = $selectRecuperacao->fetch(PDO::FETCH_ASSOC);
                    if ($resultRecuperacao != NULL) {
                        echo "<div class='mensagem-erro'>Já existe uma recuperação de senha em andamento para este usuário!</div>";
                        die();
                    }
                    $token = bin2hex(random_bytes(32));
                    $insert = $connection->prepare("INSERT INTO tbl_recuperacao (id_recuperacao, usuario, data_expiracao) VALUES (:token, :id_usuario, DATE_TRUNC('hour', NOW() + INTERVAL '1 hour'))");
                    $insert->execute(['token' => $token, 'id_usuario' => $result['id_usuario']]);
                    $subject = 'Recuperação de senha';
                    $body = "Olá, " . $result['nome'] . "! <br>
                    Você solicitou a recuperação de senha para o email $email em nosso site. <br>
                    <a href='https://projetoscti.com.br/projetoscti12/recuperacao_senha.php?token=$token'>Clique aqui</a> para recuperar sua senha. <br>
                    Você tem até uma hora para recuperar sua senha. <br>
                    Caso não tenha sido você, ignore este email. <br>
                    <br><br>
                    <img src='cid:logo' alt='Logo Mascotero' style='width: 200px; height: 240px;'>";
                    if (sendEmail($email, $subject, $body, $mail)) {
                        echo "<div class='mensagem-sucesso'>Email enviado com sucesso!</div>";
                        setcookie('email', $email, time() + 1209600);
                    }
                    else {
                        echo "<div class='mensagem-erro'>Erro ao enviar email: " . $mail->ErrorInfo . "</div>";
                    }
                }
            ?>
            </form>
        </div>
    </body>
</html>