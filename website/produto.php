<?php
    include("util.php");
    startSession(NULL);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Mascotero</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_header_footer.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400;1,9..40,500&display=swap" rel="stylesheet">         
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
</head>
<body>
    <header id="header">
        <?php
            $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
            header_pag($isAdmin, "produtos.php");
        ?>
    </header>
    <div id="content">
        <div class="container-geral">
            <div id="container-tela-produto">
                
            </div>
        </div>
    </div>
</body>
</html>