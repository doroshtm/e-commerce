<?php
    include("util.php");
    startSession();
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles_header_footer.css">
        <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Montserrat&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,600&display=swap" rel="stylesheet">
        <title>Carrinho | Mascotero</title>


        <!-- Adicionado para que o símbolo do carrinho esteja azul quando o usuário está na página -->
        <style>
          #simbolo-carrinho {
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
            <?php
                $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
                header_pag($isAdmin, "carrinho.php");
            ?>
        </header>
        <div id="content">
            <div class="container-geral-nocenter">
                <div id="titulo-carrinho">
                    <span class="texto-destaque">Meu carrinho</span>
                </div>
                <div id="container-lista-produtos">
                    <div id="container-carrinho-produtos">
                        <div class="produto-carrinho">
                            <div class="conteudo-carrinho">
                                <div class="texto-carrinho">
                                    <span class="titulo-carrinho">Chaveiro Mascote Informática #1</span>
                                    <span class="tags-produto">Informática</span>
                                    <span class="descricao-produto">Chaveiro do mascote Python do curso de informática</span>
                                    <div class="container-preco-aumentar">
                                        <span class="texto-destaque">R$2,00</span>
                                        <div class="aumentar-diminuir">
                                            <img src="./imagens/Diminuir_qtd.svg" class="aumentar">
                                            <span class="qtd-numero-carrinho">1</span>
                                            <img src="./imagens/Aumentar_qtd.svg" class="diminuir">
                                        </div>
                                    </div>
                                </div>
                                <div class="imagem-carrinho">
                                    <img src="./imagens/G. Carraro.jpg">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="container-carrinho-lista">
                        <div id="carrinho-lista">
                            <span id="carrinho-cabecalho">Sua compra</span>
                            <div class="separador">
                                <div class="bola-separador"></div>
                            </div>
                            <div id="lista-compras-carrinho">
                                <span class="nome-produto-carrinho">Chaveiro Mascote Informática #1</span>
                                <span class="preco-produto-carrinho">R$2,00</span>
                            </div>
                            <div class="separador">
                                <div class="bola-separador"></div>
                            </div>
                            <div id="resultado-carrinho">
                                <span class="nome-produto-carrinho">Total</span>
                                <span class="preco-produto-carrinho">R$300,00</span>
                            </div>
                            <div class="centraliza">
                                <button id="botao-finalizar">Finalizar compra</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer id="footer">
            <div id="footer-lado1">
                <div class="container-logo">
                    <img src="imagens/MC_Logo_Footer.svg" width="75px" height="75px"> Mascotero     
                </div>
            </div>
            <div id="footer-lado2">Copyright © 2023 Mascotero Ltda | Todos os direitos reservados</div>
        </footer>

    </body>
</html>