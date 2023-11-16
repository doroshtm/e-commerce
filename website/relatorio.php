<?php 
    include("util.php");
    startSession(NULL);

    $isAdmin = isset($_SESSION['user']['isAdmin']) ? $_SESSION['user']['isAdmin'] : false;
    if(!$isAdmin) {
      header("Location: ./");
    }
    $today = date('Y-m-d');
    $startOfMonth = date('Y-m-01');
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório | Mascotero</title>
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
                header_pag($isAdmin, "relatorio.php");
            ?>
        </header>
   <div id='pai'>
      <form action='./relatorio.php' method='POST' id='formlogin' style='width:60%;'>
        <div id="logo-login">
          <img src="imagens/Emblema_Mascotero.svg" alt="Logo Mascotero">
          Mascotero
        </div>
        <div id="container-grid-login">
          <div class="label-input-login">
            <label for="inicial">Data inicial</label>
            <input type="date" name="iniDate" id="inicial" value="<?php echo $startOfMonth; ?>" required>
          </div>
          <div class="label-input-login">
            <label for="final">Data final</label>
            <input type="date" name="finDate" id="final" value="<?php echo $today; ?>" required>
          </div>
          <div class="label-input-login">
            <label for="abrir_HTML">Abrir como HTML</label>
            <input type="radio" name="HTML" id="abrir_HTML" value="HTML">
          </div>
          <div class="label-input-login">
            <label for="abrir_PDF">Abrir como PDF</label>
            <input type="radio" name="HTML" id="abrir_PDF" value="PDF" checked>
          </div>
          <div class="label-input-login">
            <label for="PDF">Baixar PDF ao invés de abrir</label>
            <input type="checkbox" name="PDF" id="PDF" value="true">
          </div>
        </div>
          <input type='submit' value='Gerar'>
<?php
  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['iniDate']) || !isset($_POST['finDate']) || $_POST['iniDate'] > $_POST['finDate']) {
      header("Location: relatorio.php?message='Data inválida'");
      die();
    }
    $connection = connect();
    
    $initialDate = $_POST['iniDate'];
    $finalDate = $_POST['finDate'];


    $selectCompra = $connection->prepare("SELECT compra.id_compra, compra.data, usuario.nome, sum(compra_produto.quantidade * produto.preco) total, compra.cupom
                                          FROM tbl_compra AS compra JOIN tbl_compra_produto AS compra_produto ON compra.id_compra = compra_produto.compra
                                          JOIN tbl_produto AS produto ON compra_produto.produto = produto.id_produto
                                          JOIN tbl_usuario AS usuario ON compra.usuario = usuario.id_usuario
                                          WHERE compra.data >= :datai AND compra.data <= :dataf AND compra.status = 'PAGO'
                                          GROUP BY compra.id_compra, compra.data, usuario.nome
                                          ORDER BY compra.data");


    $selectCompraProduto = $connection->prepare("SELECT produto.nome, compra_produto.quantidade, produto.preco, (compra_produto.quantidade * produto.preco) subtotal
                                                  FROM tbl_compra_produto AS compra_produto JOIN tbl_produto AS produto ON compra_produto.produto = produto.id_produto
                                                  WHERE compra_produto.compra = :cod_compra ORDER BY produto.nome");

    // PS: Função não vai ser usada para seguir o resto do código, mas pode ser usada
    // formata valores em reais 
    // setlocale(LC_ALL, 'pt_BR.utf-8', );

    $html = "<html>";

    $selectCompra->execute(['datai' => $initialDate, 'dataf' => $finalDate]);
    $resultCompra = $selectCompra->fetchAll(PDO::FETCH_ASSOC);

    $html .= "<br>";
    foreach($resultCompra as $row) {
      $date = new DateTime($row['data']);
      $IDCompra = $row['id_compra'];
      $name    = $row['nome'];
      $total      = number_format($row['total'], 2, ',', '.');

      $nameWidth = strlen($name) * 11;
      $date       = $date->format('d/m/Y');
      $IDWidth   = strlen($IDCompra) * 11;
      $IDWidth = $IDWidth > 32 ? $IDWidth : 32;
      $totalWidth = strlen($total) * 8;
      $totalWidth = $totalWidth > 80 ? $totalWidth : 80;

      $html .= "<table border='1'>
      <tr>
        <b><td width='$IDWidth' height='30'>ID</td><td width='100' height='30'>Data</td><td width='$nameWidth' height='30'>Usuario</td><td width='$totalWidth' height='30'>Total (R$)</td></b></tr>";

      
      
      $html .= "
      <tr>
        <td width='$IDWidth' height='30'>$IDCompra</td><td width='100' height='30'>$date</td><td width='$nameWidth' height='30'>$name</td><td width='$totalWidth' height='30'>$total</td>
      </tr></table>";
      
      $selectCompraProduto->execute(['cod_compra' => $row['id_compra']]);
      $resultCompraProduto = $selectCompraProduto->fetchAll(PDO::FETCH_ASSOC);
      
      $html .= "<br><table border='1'>
      <tr>
        <td width='400' height='30'>Produto</td><td width='100' height='30'>Quantidade</td><td width='100' height='30'>Unidade (R$)</td><td width='100' height='30'>Subtotal (R$)</td></tr>";

      foreach($resultCompraProduto as $row) {
        $product = abreviarTexto($row['nome'], 40);
        $amount = $row['quantidade'];
        $unitPrice = number_format($row['preco'], 2, ',', '.');
        $subtotal = number_format($row['subtotal'], 2, ',', '.');
        
        $html .= "
      <tr>
        <td width='400' height='30'>$product</td><td width='100' height='30'>$amount</td><td width='100' height='30'>$unitPrice</td><td width='100' height='30'>$subtotal</td></tr>";
      }
      $html .= "</table><br><hr>";
    }
    $html .= "</html>";
    echo "</form></div>";
    $openHTML = isset($_POST['HTML']) ? $_POST['HTML'] == 'HTML' : false;
    if ($openHTML) {
      echo "<script src='./js/relatorio.js'></script>
      <script>esconderFormulario();</script>
      
      <div class='container-geral'><br>
      <a href='./relatorio.php' id='voltar' style='font-size: 20px'>Voltar</a><br><br>";
      echo $html;
      echo "</div></body></html>";
      die();
    }
    echo "</body></html>";
    $download = isset($_POST['PDF']) ? $_POST['PDF'] : false;
    $file = 'relatorios/relatorio' . date('mdYH:i:s', time()) . '.pdf';

    if (!createPDF($html, $file, 'Relatorio de vendas', $download)) {
      header("Location: relatorio.php?message='Erro ao gerar relatório'");
    } else {
        if (!$download) {
            header("Location: $file");
        }
    }
  }
  if (isset($_GET['message'])) {
    echo "<script>alert(" . $_GET['message'] . ")</script>";
    header("refresh:0;url=relatorio.php");
  }
?>
