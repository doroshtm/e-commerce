<?php
    include("util.php");
    $sessID = startSession();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="icon" type="image/x-cion" href="/website/imagens/MC_Logo_Footer.svg">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/cadastro.js?v=0.21"></script>
    <title>Cadastro</title>
</head>
<body>
    <div id="pai">
        <form name='formcadastro' method='post' action='./cadastro.php' id="formlogin">
            <div id="logo-login">
                <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                Mascotero
            </div>
            <div id="label-input-login">
                <label for="email">Email</label>
                <input type='text' name='email' id="email" placeholder="Seu email aqui..." required>
            </div>
            <div id="label-input-login">
                <label for="name">Nome</label>
                <input type='text' name='name' id="name" placeholder="Seu nome completo aqui..." required>
            </div>
            <div id="label-input-login">
                <label for="phone">Telefone</label>
                <input type='tel' name='phone' minLength=15 maxlength=15 onkeyup='formatPhone(this, event)' placeholder="Seu telefone aqui..."
                pattern='\([0-9]{2}\) [0-9]{5}-[0-9]{4}' required>            </div>
            <div id="label-input-login">
                <label for="password">Senha</label>
                <input type='password' name='password' id="password" maxlength="255" placeholder="Sua senha aqui..." required>
            </div>
            <div id="label-input-login">
                <label for="cpf">CPF</label>
                <input type='text' id="cpf" name='cpf' minlength=14 maxlength=14 required placeholder="Seu CPF aqui..."
                pattern='[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}' onkeyup='formatCPF(this, event)'>
            </div>
            <div id="label-input-login">
                <label for="address">Endereço (opcional)</label>
                <input type='address' name='address' id="address" placeholder="Seu endereço aqui..." maxlength=255>
            </div>
            <div id="label-input-login">
                <label for="cep">CEP (opcional)</label>
                <input type='text' id='cep' name='cep' minlength=9 maxlength=9 pattern='[0-9]{5}-[0-9]{3}' placeholder="Seu CEP aqui..."
                onkeyup='formatCEP(this, event)'>
            </div>
            <input type='submit' value='Cadastre-se'>
            <a href='cadastro.php'>Já tem conta? Cadastre-se</a>
        <?php
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $phone = $_POST["phone"];
                $phone = preg_replace('/\D+/', '', $phone);
                if (!preg_match("/^(\d{2})(\d{5})(\d{4})$/", $phone, $matches)) {
                    echo "<div class='mensagem-erro'>Telefone inválido!</div>";
                    die();
                }
                $email = $_POST['email'];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "<div class='mensagem-erro'>E-mail inválido!</div>";
                    die();
                }
                $cpf = $_POST["cpf"];
                $cpf = preg_replace('/\D+/', '', $cpf);
                if (!preg_match("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", $cpf, $matches)) {
                    echo "<div class='mensagem-erro'>CPF inválido!</div>";
                    die();
                }
                if (isset($_POST['cep']) && $_POST['cep'] != "") {
                    $cep = $_POST["cep"];
                    $cep = preg_replace('/\D+/', '', $cep);
                    if (!preg_match("/^(\d{5})(\d{3})$/", $cep, $matches)) {
                        echo "<div class='mensagem-erro'>CEP inválido!</div>";
                        die();
                    }
                }
                $connection = connect();
                $date = date('m/d/Y');
                $select = $connection->prepare('select id_usuario from usuarios where email = :email');
                $select->execute(['email' => $email]);
                $result = $select->fetch(PDO::FETCH_ASSOC);
                if ($result != NULL) {
                    echo "<div class='mensagem-erro'>E-mail já cadastrado!</div>";
                    die();
                }

                $user = ['nome' => $_POST['name'], 'email' => $email, 'senha' => $_POST['password'], 'telefone' => $_POST['phone'], 'cpf' => $_POST['cpf'], 'cep' => $_POST['cep'], 'endereco' => $_POST['address'], 'data_cadastro' => $date];
                $insert = $connection->prepare('insert into usuarios (id_usuario, nome, email, senha, telefone, cpf, cep, endereco, data_cadastro) values (DEFAULT, :nome, :email, :senha, :telefone, :cpf, :cep, :endereco, :data_cadastro)');
                $insert->execute($user);
                $select = $connection->prepare('SELECT * FROM usuarios WHERE id_usuario = currval(\'usuarios_id_usuario_seq\')');
                $select->execute();
                $result = $select->fetch(PDO::FETCH_ASSOC);
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
