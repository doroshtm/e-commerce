<?php
    include("util.php");
    startSession();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotero</title>
    <link rel="stylesheet" href="styles_header_footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;700&display=swap"> 
    <link rel="stylesheet" href="styles_paginas.css">
    <script src="js/script.js"></script>
    <link rel="icon" type="image/svg+xml" href="./imagens/MC_Logo_Footer.svg">

</head>
<body>
    <header id="header">
        <div class="container-logo"> 
            <div id="imagem-logo"><img src="imagens/MC_Logo_Header.svg"> </div>
            <div id="texto-logo">Mascotero</div>
        </div>
        <nav id="nav-header">
            <ul>
                <li><a href="./">Home</a></li>
                <li><a href="sobre.php" id="nav-atual">Sobre</a></li>
                <li><a href="produtos.php">Produtos</a></li>
                <li><a href="contato.php">Contato</a></li>
            </ul>
        </nav>
        <?php
            $name = isset($_SESSION['name']) ? explode(' ', $_SESSION['name'], 2)[0] : 'visitante';
            $isAdmin = isset($_SESSION['isAdmin']) ? $_SESSION['isAdmin'] : false;
        ?>
        <div id="container-usuario">
            <span>Olá, </span>
            <span id="nome-usuario"> <?php echo "$name - você é " . ($isAdmin ? 'admin' : 'cliente') ?> </span>
            <a href = './login.php'> <img src="imagens/user_icon.svg" alt="Foto do cliente"> </a>
            <img src="imagens/carrinho.svg" alt="Carrinho de compras">
        </div>
        </header>
    <div id="content">
        <div class="container-geral">
            <div id=sobre-containerlogo>
                <span class="texto-destaque">Sobre</span>
                <div class="separador">
                    <div class="bola-separador"></div>
                </div>
                <div id="sobre-mascotero">
                        <div id="sobre-mascotero-logo">
                            <img src="./imagens/Emblema_Mascotero.svg" id="foto_logo">
                        </div>
<!--                     <img src="/website/imagens/Sobre_Mascotero.svg"> -->
                    <div id="sobre-mascotero-texto">
                        <div id="sobre-mascotero-marca">
                            <div class="centraliza"><span class="texto-destaque">Nossa marca</span> </div>
                            <div class="conteudo-mp">
                                <div class="texto-mp">
                                    <span class="texto-destaque">Nosso nome<br></span>
                                    <span>O nome da nossa marca, 'Mascotero', é uma combinação entre várias palavras diferentes.
                                        A primeira refere-se ao nosso principal produto: o chaveiro. A segunda, ao conteúdo presente 
                                        nos nossos produtos, os mascotes representantes de cada curso. A terceira palavra, "Escoteiro", 
                                        é incorporada na logo.
                                    </span>
                                </div>
                            </div>
                            <div class="conteudo-mp">
                                <div class="texto-mp">
                                    <span class="texto-destaque">Nossa logo<br></span>
                                    <span>A logo é feita inspirada em um emblema de escoteiros, utilizando de um símbolo 
                                        minimalista de uma pata de animal, assim se ligando ao contexto de 'mascote' promovido 
                                        pela marca. Além disso, os dois aros na parte de cima e embaixo da logo é colocado para 
                                        causar semelhança com um chaveiro.
                                    </span>
                                </div>
                            </div>
                            <div class="conteudo-mp">
                                <div class="texto-mp">
                                    <span class="texto-destaque">Nossa visão<br></span>
                                    <span>A Mascotero foi criada tendo em mente os alunos, e interessados, no CTI. 
                                        Os mascotes são criados tendo em mente a opinião e identidade dos alunos de 
                                        cada um dos cursos técnicos oferecidos para o CTI.
                                        <br>
                                        
                                        O produto, que carrega o símbolo de mascote segue simples regras: Baixo custo, 
                                        de fácil acesso e sempre menor que a palma da sua mão!
                                    </span>
                                </div>
                            </div>
                            <div class="voltar-logo"><a href="#sobre-mascotero-logo">Clique na logo para ver sobre a &nbsp<span class="texto-destaque">equipe</span></a></div>
                        </div>
                        <div id="sobre-mascotero-equipe">
                            <div class="centraliza"><span class="texto-destaque">Nossa equipe</span></div>
                            <div class="centraliza">
                                <div id="texto-nossa-equipe">
                                Nossa equipe foi formada por ordem alfabética para o projeto final do 2º ano do 
                                curso de Informática do Colégio Técnico Industrial "Prof. Isaac Portal Roldán". 
                                A equipe teve contribuição não só para o desenvolvimento do site, como para o 
                                comércio como um todo. 
                                </div>
                            </div>
                            <div class="conteudo-mp">
                                <div class="imagem-mp">
                                        <img src="./imagens/Emily.png">
                                </div>
                                    <div class="texto-mp">
                                        <span class="texto-destaque"> #11 - Emily Rocha<br></span>
                                        <span> Coleta de satisfação dos mascotes, assistente em <em>design</em>
                                        </span>
                                    </div>
                            </div>
                    <div class="conteudo-mp">
                        <div class="imagem-mp">
                                <img src="./imagens/Ellen.jpg">
                        </div>
                            <div class="texto-mp">
                                <span class="texto-destaque"> #12 - Ellen Caroline Carvalho<br></span>
                                <span><em>Marketing</em> e busca de fornecedores
                                </span>
                            </div>
                    </div>
                    <div class="conteudo-mp">
                        <div class="imagem-mp">
                                <img src="./imagens/Fernando.jpg">
                            </div>
                            <div class="texto-mp">
                                <span class="texto-destaque"> #13 - Fernando Ellerbrock Theodoro<br></span>
                                <span>Gerente Geral do projeto, desenvolvedor <em>front-end</em> do site
                                </span>
                            </div>
                    </div>
                    <div class="conteudo-mp">
                        <div class="imagem-mp">
                                <img src="./imagens/G. Carraro.jpg">
                            </div>
                            <div class="texto-mp">
                                <span class="texto-destaque"> #14 - Gabriel Carraro Salzedas<br></span>
                                <span>Líder técnico do projeto, desenvolvedor <em>back-end</em> do site
                                </span>
                            </div>
                    </div>
                    <div class="conteudo-mp">
                        <div class="imagem-mp">
                                <img src="./imagens/G. Menegazzo_alt.jpg">
                            </div>
                            <div class="texto-mp">
                                <span class="texto-destaque"> #15 - Gabriel Eugênio Menegazzo<br></span>
                                <span>Líder da equipe de <em>design</em>, principal artista de produtos e logo
                                </span>
                            </div>
                    </div>
                    <div class="voltar-logo"><a href="#sobre-mascotero-logo">Clique na logo para ver sobre a &nbsp<span class="texto-destaque">marca</span></a></div>
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