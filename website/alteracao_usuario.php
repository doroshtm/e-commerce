<?php
    include("util.php");
    startSession(NULL);
    if(!isset($_SESSION['user']['isAdmin']) || !$_SESSION['user']['isAdmin']) {
        header("Location: ./");
    }
    $id = $_GET['id'];
    $connection = connect();
    isset($_POST['pass']) ? $pass = $_POST['pass'] : "";
    if (isset($pass)) {
        $select_user = $connection->prepare("SELECT senha FROM tbl_usuario WHERE id_usuario = :id_admin");
        $select_user->execute(['id_admin' => $_SESSION['user']['id']]);
        $result = $select_user->fetch(PDO::FETCH_ASSOC);
        if($result['senha'] != $pass) {
            echo "<script>alert('Senha incorreta!')</script>";
            header("Refresh: 0; url=./alteracao_usuario.php?id=$id");
            die();
        }
        $delete = $connection->prepare("DELETE FROM tbl_usuario WHERE id_usuario = :id");
        $delete->execute(['id' => $id]);
        header("Location: ./usuarios.php");
        die();
    }
    $select_user = $connection->prepare("SELECT nome, email, admin, telefone, endereco, cep, cpf, data_cadastro FROM tbl_usuario WHERE id_usuario = :id");
    $select_user->execute(['id' => $id]);
    $result = $select_user->fetch(PDO::FETCH_ASSOC);
    if($result == NULL) {
        header("Location: ./usuarios.php?message='Usuário não encontrado!'");
        die();
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <script src="js/cadastro.js?v=0.21"></script>
        <script src="js/util.js?v=0.21"></script>
        <title>Alterar usuário | <?php echo $id ?> | Mascotero</title>
    </head>
    <body>
        <div id="pai">
            <dialog id="popup-confirmar">
                <div class="content-popup">
                    <span class="texto-destaque">Tem certeza que deseja deletar o usuário?</span>
                    <input type="checkbox" id="mostrar-confirmacao">
                    <label for="mostrar-confirmacao" id="confir">Sim</label>
                    <form id="formlogin" style="margin:0; padding:0;" method="POST" action="./alteracao_usuario.php?id=<?php echo $id ?>">
                        <div class="label-input-login">
                            <label for="pass"> Digite sua senha</label>
                            <input type="password" id="pass" name="pass" placeholder="Senha para confirmação..." required>
                        </div>
                        <input type="submit">
                    </form>
                    <button id="botao-fechar-popup" class="botao-finalizar">Fechar</button> 
                </div>
            </dialog>
            <form name="alterarUsuario" method="post" action="./alteracao_usuario.php?id=<?php echo $id ?>" id="formlogin" style="width:60%;">
                <div id="logo-login">
                    <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
                    Mascotero
                </div>
                <div id="container-grid-login">
                    <div class="label-input-login">
                        <label for="nome">Nome do usuário</label>
                        <input type="text" id="nome" name="nome" placeholder="Nome do usuário" required value="<?php echo $result['nome'] ?>"><br>
                    </div>
                    <div class="label-input-login">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" required value="<?php echo $result['email'] ?>"><br>
                    </div>
                    <div class="label-input-login">
                        <label for="admin">Admin</label>
                        <select name="admin" id="admin" required>
                            <option <?php echo $result['admin'] == 0 ? 'selected' : '' ?> value="0">Não</option>
                            <option <?php echo $result['admin'] == 1 ? 'selected' : '' ?> value="1">Sim</option>
                        </select><br>
                    </div>
                    <div class="label-input-login">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" placeholder="Telefone" required value="<?php echo $result['telefone'] ?>" pattern="\([0-9]{2}\) [0-9]{5}-[0-9]{4}" onkeyup="formatPhone(this, event)" maxlength=15 minlength=15><br>
                    </div>
                    <div class="label-input-login">
                        <label for="endereco">Endereço</label>
                        <input type="text" id="endereco" name="endereco" placeholder="Endereço" value="<?php echo $result['endereco'] ?>"><br>
                    </div>
                    <div class="label-input-login">
                        <label for="cep">CEP</label>
                        <input type="text" id="cep" name="cep" placeholder="CEP" value="<?php echo $result['cep'] ?>" pattern="[0-9]{5}-[0-9]{3}" onkeyup="formatCEP(this, event)" maxlength=9 minlength=9><br>
                    </div>
                    <div class="label-input-login">
                        <label for="cpf">CPF</label>
                        <input type="text" id="cpf" name="cpf" placeholder="CPF" required value="<?php echo $result['cpf'] ?>" pattern="[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}" onkeyup="formatCPF(this, event)" maxlength=14 minlength=14><br>
                    </div>
                    <div class="label-input-login">
                        <label for="data_cadastro">Data do cadastro</label>
                        <?php
                            $date = new DateTime($result['data_cadastro']);
                            $date = $date->format('Y-m-d');
                            echo "<input type='date' id='data_cadastro' placeholder='Data do cadastro' readonly value= '" . $date . "'>";
                        ?>
                    </div>
                </div>
                <input type="submit" value="Alterar">
                <input type="button" value="Remover" id="botao-abrir-popup">
                <input type="button" value="Cancelar" onclick="window.history.back()">
                <?php
                    if($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $name = $_POST['nome'];
                        $email = $_POST['email'];
                        $isAdmin = $_POST['admin'];
                        $phone = $_POST['telefone'];
                        $address = $_POST['endereco'];
                        $cep = $_POST['cep'];
                        $cpf = $_POST['cpf'];
                        $update = $connection->prepare("UPDATE tbl_usuario SET nome = :name, email = :email, admin = :isAdmin, telefone = :phone, endereco = :address, cep = :cep, cpf = :cpf WHERE id_usuario = :id");
                        $update->execute(array(
                            ':name' => $name,
                            ':email' => $email,
                            ':isAdmin' => $isAdmin,
                            ':phone' => $phone,
                            ':address' => $address,
                            ':cep' => $cep,
                            ':cpf' => $cpf,
                            ':id' => $id
                        ));
                        header("Location: ./usuarios.php");
                    }
                ?>
            </form>
        </div>
    </body>
</html>