<html>
<body>
    <?php
        include("util.php");
        $sessID = startSession();
        $connection = connect();
        $date = date('m/d/Y');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
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
    <form name='formcadastro' method='post' action='./cadastro.php'>
        <table><tr>
        <td>E-mail<br>
        <input type='email' name='email' size=30 required></td>
        <td>Nome<br>
        <input type='text' name='name' size=40 required></td>
        <td>Telefone<br>
        <input type='tel' pattern='[0-9]{2} [0-9]{5}-[0-9]{4}' name='phone' size=14 minlength=11 maxlength=17 required></td>
        <td>Senha<br>
        <input type='password' name='password' size=10 maxlength=255 required>
        <td>CPF<br>
        <input type='text' name='cpf' size=11 minlength=11 maxlength=14 required></td>
        <td>Endereço (opcional)<br>
        <input type='text' name='address' size=40 maxlength=255></td>
        <td>CEP (opcional)<br>
        <input type='text' name='cep' size=8 minlength=8 maxlength=9></td>
        </tr></table>
        <input type='submit' value='Enviar'></td>
    </form>
</body>
</html>