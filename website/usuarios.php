<?php
    include("util.php");
    startSession(NULL);
    $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
    if(!$isAdmin) {
        header('Location: ./');
    }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    <title>Usuário | Mascotero</title>

     <!-- Para que o símbolo fique azul quando na página de tabela de usuários  -->
    <style>
          #simbolo-usuario {
                filter: brightness(0) invert(19%) sepia(95%) saturate(3970%) hue-rotate(244deg) brightness(101%) contrast(106%);
            }
    </style>

</head>
<body>
    <header id="header">
        <?php header_pag($isAdmin, "usuarios.php"); ?>
    </header>
    <div id="content">
        <div class="container-geral">
<?php
    $connection = connect();
    $select = $connection->prepare('SELECT * FROM tbl_usuario ORDER BY id_usuario');
    $select->execute();
    $result = $select->fetchAll(PDO::FETCH_ASSOC);
    echo "
    <div style='position:relative;'>
    <div id='table-usuario-wrapper'>
    <table border=1>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Admin</th>
            <th>Senha</th>
            <th>Telefone</th>
            <th>Endereço</th>
            <th>CEP</th>
            <th>CPF</th>
            <th>Data do cadastro</th>
            <th>Ações</th>
        </tr>
        </thead>";
    foreach($result as $row) {
        $usuarioAdmin = $row['admin'] != null;
        $admin = $usuarioAdmin? "Sim": "Não";
        $date = new DateTime($row['data_cadastro']);
        $row['data_cadastro'] = $date->format('d/m/Y');
        echo "
        <tr>
            <td>" . $row['id_usuario'] . "</td>
            <td>" . $row['nome'] . "</td>
            <td>" . $row['email'] . "</td>
            <td style='padding:20px;'>" . $admin . "</td>
            <td>" . $row['senha'] . "</td>
            <td>" . $row['telefone'] . "</td>
            <td>" . $row['endereco'] . "</td>
            <td>" . $row['cep'] . "</td>
            <td>" . $row['cpf'] . "</td>
            <td>" . $row['data_cadastro'] . "</td>
            <td>
                <a href='./alteracao_usuario.php?id=" . $row['id_usuario'] . "'><img src='./imagens/editar.png' alt='Editar' width='24px' height='24px'></a>
            </td>
        </tr>";
    }
    echo "</table> </div> </div>";
?>
    </div>
</div>
<footer id="footer">
            <div id="footer-lado1">
                <div class="container-logo">
                    <img src="imagens/MC_Logo_Footer.svg" width="75px" height="75px" alt= "Logo do footer"> Mascotero
                </div>
            </div>
            <a id="footer-lado2" href="sobre.php">
                    <span class="texto-destaque">Desenvolvedores</span>
                    <span>11 - Ellen Carvalho </span>
                    <span>12 - Emily Rocha</span>
                    <span>13 - Fernando Theodoro </span>
                    <span>14 - Gabriel Carraro </span>
                    <span>15 - Gabriel Menegazzo </span>
            </a>
        </footer>
</body>
</html>
<?php
    if(isset($_GET['message'])) {
        echo "<script>alert(" . $_GET['message'] . ");</script>";
        header("refresh: 0; url=./usuarios.php");
    }
?>