<?php
    include("util.php");
    $sessID = startSession();
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
        <script src="js/cadastro.js?v=0.21"></script>
        <script src="js/validaSenha.js"></script>
        <title>Cadastro | Mascotero</title>
    </head>
    <body>
        <div id="pai">
            <form name="formcadastro" method="post" action="./cadastro.php?url=<?php echo isset($_GET['url']) ? $_GET['url'] : 'index.php'; ?>" id="formlogin">
                <div id="logo-login">
                    <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                    Mascotero
                </div>
                <div class="label-input-login">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="Seu email aqui..." required>
                </div>
                <div class="label-input-login">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name" placeholder="Seu nome completo aqui..." required>
                </div>
                <div class="label-input-login">
                    <label for="phone">Telefone</label>
                    <input type="tel" name="phone" minLength=15 maxlength=15 onkeyup="formatPhone(this, event)" placeholder="Seu telefone aqui..."
                    pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" required>            </div>
                <div class="label-input-login">
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" maxlength="255" placeholder="Sua senha aqui..." required>
                </div>
                <div class="label-input-login">
                    <label for="password">Confirme a senha</label>
                    <input type="password" name="password2" id="password2" maxlength="255" placeholder="Sua senha aqui..." required>
                </div>
                <div class="label-input-login">
                    <label for="cpf">CPF</label>
                    <input type="text" id="cpf" name="cpf" minlength=14 maxlength=14 required placeholder="Seu CPF aqui..."
                    pattern="[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}" onkeyup="formatCPF(this, event)">
                </div>
                <div class="label-input-login">
                    <label for="address">Endereço (opcional)</label>
                    <input type="address" name="address" id="address" placeholder="Seu endereço aqui..." maxlength=255>
                </div>
                <div class="label-input-login">
                    <label for="cep">CEP (opcional)</label>
                    <input type="text" id="cep" name="cep" minlength=9 maxlength=9 pattern="[0-9]{5}-[0-9]{3}" placeholder="Seu CEP aqui..."
                    onkeyup="formatCEP(this, event)">
                </div>
                <div id="container-lembrar-senha">
                    <input type="checkbox" name="rememberme" id="lembrar-senha">
                    <label for="lembrar-senha">Mantenha-me conectado</label>
                </div>
                <input type="button" value="Cadastre-se" onclick="validatePassword()">
                <a href="login.php<?php echo isset($_GET['url']) ? '?url=' . $_GET['url'] : ''; ?>" id="link-login">Já tem conta? Conecte-se</a>
            <?php
                
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['password2']) || empty($_POST['phone']) || empty($_POST['cpf'])) {
                        echo "<script>alert('Preencha todos os campos!')</script>";
                        echo "<div class='mensagem-erro'>Preencha todos os campos!</div>";
                        die();
                    }
                    $phone = $_POST["phone"];
                    $phone = preg_replace('/\D+/', '', $phone);
                    if (!preg_match("/^(\d{2})(\d{5})(\d{4})$/", $phone, $matches)) {
                        echo "<script>alert('Telefone inválido!')</script>";
                        echo "<div class='mensagem-erro'>Telefone inválido!</div>";
                        die();
                    }
                    $email = $_POST['email'];
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        echo "<script>alert('E-mail inválido!')</script>";
                        echo "<div class='mensagem-erro'>E-mail inválido!</div>";
                        die();
                    }
                    $cpf = $_POST["cpf"];
                    $cpf = preg_replace('/\D+/', '', $cpf);
                    if (isset($_POST["cpf"]) && !preg_match("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", $cpf, $matches)) {
                        echo "<script>alert('CPF inválido!')</script>";
                        echo "<div class='mensagem-erro'>CPF inválido!</div>";
                        die();
                    }
                    if (isset($_POST['cep']) && $_POST['cep'] != "") {
                        $cep = $_POST["cep"];
                        $cep = preg_replace('/\D+/', '', $cep);
                        if (!preg_match("/^(\d{5})(\d{3})$/", $cep, $matches)) {
                            echo "<script>alert('CEP inválido!')</script>";
                            echo "<div class='mensagem-erro'>CEP inválido!</div>";
                            die();
                        }
                    }
                    if ($_POST['password'] != $_POST['password2']) {
                        echo "<script>alert('Senhas não coincidem!')</script>";
                        echo "<div class='mensagem-erro'>Senhas não coincidem!</div>";
                        die();
                    }
                    $connection = connect();
                    $date = date('m/d/Y');
                    $select = $connection->prepare('select id_usuario from tbl_usuario where email = :email');
                    $select->execute(['email' => $email]);
                    $result = $select->fetch(PDO::FETCH_ASSOC);
                    if ($result != NULL) {
                        echo "<script>alert('E-mail já cadastrado!')</script>";
                        echo "<div class='mensagem-erro'>E-mail já cadastrado!</div>";
                        die();
                    }

                    $insert = $connection->prepare("INSERT INTO tbl_usuario (nome, email, senha, telefone, cpf, cep, endereco, data_cadastro) VALUES (:name, :email, :password, :phone, :cpf, :cep, :address, :signup_date)");
                    $insert->execute(array(
                        ':name' => $_POST['name'],
                        ':email' => $email,
                        ':password' => $_POST['password'],
                        ':phone' => $phone,
                        ':cpf' => $cpf,
                        ':cep' => $_POST['cep'],
                        ':address' => $_POST['address'],
                        ':signup_date' => $date
                    ));
                    $_SESSION['user']['id'] = $connection->lastInsertId();
                    $_SESSION['user']['name'] = $_POST['name'];
                    $_SESSION['user']['email'] = $_POST['email'];
                    $_SESSION['user']['password'] = $_POST['password'];
                    $_SESSION['user']['phone'] = $_POST['phone'];
                    $_SESSION['user']['isAdmin'] = false;
                    $_SESSION['user']['cpf'] = $_POST['cpf'];
                    $_SESSION['user']['date'] = $date;
                    !empty($_POST['cep']) ? $_SESSION['user']['cep'] = $_POST['cep'] : '';
                    !empty($_POST['address']) ? $_SESSION['user']['address'] = $_POST['address'] : '';
                    if ($_POST['rememberme'] == 'on') {
                        $tokenREMEMBER = generateToken();
                        setcookie('loginCookie', $tokenREMEMBER, time() + 1209600);
                        insertToken($tokenREMEMBER, $_SESSION['user']['id']);
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
