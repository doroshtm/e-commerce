<?php
    include("util.php");
    startSession();
    if(!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
        header('Location: ./');
    }
    $id = $_GET['id'];
    $connection = connect();
    $select_user = $connection->prepare('select * from tbl_usuario where id_usuario = ' . $id);
    $select_user->execute();
    $result = $select_user->fetch(PDO::FETCH_ASSOC);
    if($result == NULL) {
        echo "Usuário não encontrado! Redirecionando para a página de usuários...";
        header('Refresh: 3; url=./usuarios.php');
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
        <title>Alterar usuário | <?php echo $id ?> | Mascotero</title>
    </head>
    <body>
        <div id="pai">
            <form method="get" id="formDelUsuario" action="./remocao_usuario.php"></form>
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
                        <input type="date" id="data_cadastro" placeholder="Data do cadastro" readonly value="<?php echo $result['data_cadastro'] ?>"><br>
                    </div>
        </div>
    </body>
</html>