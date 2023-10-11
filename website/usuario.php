<?php
    include("util.php");
    startSession();
    $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="stylesheet" href="styles_paginas.css">
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="stylesheet" href="styles_paginas.css">
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
        <div class="container-logo"> 
                <div id="imagem-logo"><img src="imagens/MC_Logo_Header.svg"> </div>
                <a href="index.php" style="all:unset;">
                <div id="texto-logo">Mascotero</div>
            </a> 
        </div>
            <nav id="nav-header">
                <ul>
                    <li><a href="./">Home</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="produtos.php">Produtos</a></li>
                    <li><a href="contato.php">Contato</a></li>
                </ul>
            </nav>
        <?php header_pag($isAdmin, "usuario.php"); ?>
    </header>
    <div id="content">
        <div class="container-geral">
<?php
    if ($isAdmin) {
        $connection = connect();
        $select = $connection->prepare('select * from tbl_usuario');
        $select->execute();
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
        echo "
        <table border=1>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Admin</th>
                <th>Senha</th>
                <th>Telefone</th>
                <th>Endereco</th>
                <th>CEP</th>
                <th>CPF</th>
                <th>Data do cadastro</th>
            </tr>";
        foreach($result as $row) {
            $usuarioAdmin = $row['admin'] != null;
            $admin = $usuarioAdmin? "Sim": "Não";
            echo "
            <tr>
                <td>" . $row['id_usuario'] . "</td>
                <td>" . $row['nome'] . "</td>
                <td>" . $row['email'] . "</td>
                <td>" . $admin . "</td>
                <td>" . $row['senha'] . "</td>
                <td>" . $row['telefone'] . "</td>
                <td>" . $row['endereco'] . "</td>
                <td>" . $row['cep'] . "</td>
                <td>" . $row['cpf'] . "</td>
                <td>" . $row['data_cadastro'] . "</td>
            </tr>";
        }
        echo "</table>";


    } else {
        header('Location: ./');
    }
?>
    </div>
</div>
<footer id="footer">
            <div id="footer-lado1" style="align-items:center;">
                <div class="container-logo">
                    <img src="imagens/MC_Logo_Footer.svg" width="75px" height="75px" alt= "Logo do footer"> Mascotero        
                </div>
            </div>
            <div id="footer-lado2">Copyright © 2023 Mascotero Ltda | Todos os direitos reservados</div>
        </footer>
</body>
</html>