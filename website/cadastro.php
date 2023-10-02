<html>
    <head>
        <title>Cadastro</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/estilo.css">
        <script src="js/util.js?v=0.21"></script>
    </head>
    <body>
        <?php
            include("util.php");
            $sessID = startSession();
        ?>
        <form name='formcadastro' method='post' action='./cadastro.php'>
            <table><tr>
            <td>E-mail<br>
            <input type='email' name='email' size=30 required></td>
            <td>Nome<br>
            <input type='text' name='name' size=40 required></td>
            <td>Telefone<br>
            <input type='tel' name='phone' size=15 minLength=15 maxlength=15 onkeyup='formatPhone(this, event)' pattern='\([0-9]{2}\) [0-9]{5}-[0-9]{4}' required></td>
            <td>Senha<br>
            <input type='password' name='password' size=10 maxlength=255 required>
            <td>CPF<br>
            <input type='text' name='cpf' size=11 minlength=14 maxlength=14 required pattern='[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}' onkeyup='formatCPF(this, event)'></td>
            <td>Endereço (opcional)<br>
            <input type='text' name='address' size=40 maxlength=255></td>
            <td>CEP (opcional)<br>
            <input type='text' name='cep' size=9 minlength=9 maxlength=9 pattern='[0-9]{5}-[0-9]{3}' onkeyup='formatCEP(this, event)'></td>
            </tr></table>
            <input type='submit' value='Enviar'></td>
        </form>
        
        <?php
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $phone = $_POST["phone"];
                $phone = preg_replace('/\D+/', '', $phone);
                if (!preg_match("/^(\d{2})(\d{5})(\d{4})$/", $phone, $matches)) {
                    echo "Telefone inválido!";
                    die();
                }
                $email = $_POST['email'];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo "E-mail inválido!";
                    die();
                }
                $cpf = $_POST["cpf"];
                $cpf = preg_replace('/\D+/', '', $cpf);
                if (!preg_match("/^(\d{3})(\d{3})(\d{3})(\d{2})$/", $cpf, $matches)) {
                    echo "CPF inválido!";
                    die();
                }
                if (isset($_POST['cep']) && $_POST['cep'] != "") {
                    $cep = $_POST["cep"];
                    $cep = preg_replace('/\D+/', '', $cep);
                    if (!preg_match("/^(\d{5})(\d{3})$/", $cep, $matches)) {
                        echo "CEP inválido!";
                        die();
                    }
                }
                $connection = connect();
                $date = date('m/d/Y');
                $select = $connection->prepare('select id_usuario from usuarios where email = :email');
                $select->execute(['email' => $email]);
                $result = $select->fetch(PDO::FETCH_ASSOC);
                if ($result != NULL) {
                    echo "E-mail já cadastrado!";
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
    </body>
</html>