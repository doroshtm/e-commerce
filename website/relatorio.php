<?php 
   include("util.php");

   // calcula hoje
   $hoje = date('Y-m-d');
   // calcula ontem
   $ontem = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $hoje ) ) ));
  
   echo "
     <form action='' method='POST'>
      Data inicial<br><input type='date' name='datai' value='$ontem'><br>
      Data final<br><input type='date' name='dataf' value='$ontem'><br>
      <input type='submit' value='Gerar'>
     </form> ";

   if ( $_POST ) {
      // faz conexao 
      $conn = conecta();

      $datai = $_POST['datai'];
      $dataf = $_POST['dataf'];

      $SQLCompra = 
              "select compras.cod_compra, compras.data, usuarios.nome, 
                  sum ( compra_produto.quantidade * produtos.valor ) total  
                from compras 
                  inner join usuarios on compras.fk_cod_usuario = usuarios.cod_usuario 
                  inner join compra_produto on compra_produto.fk_cod_compra = compras.cod_compra
                  inner join produtos on produtos.cod_produto = compra_produto.fk_cod_produto 
                where 
                  compras.data >= :datai and compras.data <= :dataf and 
                  compras.status = 'Concluida'  
                group by 
                  compras.cod_compra, compras.data, usuarios.nome 
                order by compras.data "; 

      $SQLItensCompra = 
                "select produtos.descricao, compra_produto.quantidade, produtos.valor, 
                  compra_produto.quantidade * produtos.valor subtotal 
                from compra_produto  
                  inner join produtos on produtos.cod_produto = compra_produto.fk_cod_produto 
                where 
                  compra_produto.fk_cod_compra = :cod_compra   
                order by produtos.descricao "; 
    
      /*   m o d e l o
      Cod  Data        Cliente               $ Total
        1  20/10/2023  JOAO DA SILVA        10000,00
          Produto      Qt   Unit        Sub
          CHAVEIRO      2   50,00    100,00
          BOTOM         1   10,00     10,00
      */
  
      // formata valores em reais 
      setlocale(LC_ALL, 'pt_BR.utf-8', );

      $html = "<html>";

      // abre a consulta de COMPRA do periodo
      $compra = $conn->prepare($SQLCompra);
      $compra->execute ( [ 'datai' => $datai, 
                          'dataf' => $dataf ] );
      // prepara os ITENS     
      $itens_compra = $conn->prepare($SQLItensCompra);

      
      // fetch significa carregar proxima linha
      // qdo nao tiver mais nenhuma retorna FALSE pro while
    
      /////////////  M E S T R E ////////////////////   
      // carrega a proxima linha COMPRA
      $html .= "<br><br>
              <b>".
              sprintf('%3s', 'Id').
              sprintf('%12s','Data').
              sprintf('%50s','Nome').
              sprintf('%10s','$ tot').
              "</b>
              <br>";

      while ( $linha_compra = $compra->fetch() )  
      {
        $cod_compra = sprintf('%03s',$linha_compra['cod_compra']);
        $data       = sprintf('%12s',$linha_compra['data']);
        $cliente    = sprintf('%50s',$linha_compra['nome']);
        $total      = sprintf('%10s',number_format($linha_compra['total'], 2, ',', '.'));
        
        $html .= $cod_compra . $data . $cliente . $total . "<br>";               
            
        // executa ITENS passando o codigo da COMPRA atual
        $itens_compra->execute( [ 'cod_compra' => 
                              $linha_compra['cod_compra'] ] );

        $html .= "<b>".
              sprintf('%20s','Prod').
              sprintf('%5s','Qtd').
              sprintf('%10s','$ unit').
              sprintf('%10s','$ sub').
              "</b><br>";

        /////////////  D E T A L H E  ////////////////////
        // carrega a proxima linha ITENS_COMPRA
        while ( $linha_itens_compra = $itens_compra->fetch() ) 
        {
          $produto  = sprintf('%20s',$linha_itens_compra['descricao']);
          $qtd      = sprintf('%5s',$linha_itens_compra['quantidade']);
          $unit     = sprintf('%10s',number_format($linha_itens_compra['valor'], 2, ',', '.'));
          $subtotal = sprintf('%10s',number_format($linha_itens_compra['subtotal'], 2, ',', '.'));

          $html .= $produto . $qtd . $unit . $subtotal . "<br>";
        } 
      }

      $html.="</html>";
      echo $html;
      /*
      if ( CriaPDF ( 'Relatorio de Vendas', 
                      $html, 
                      'relatorios/relatorio.pdf' ) )  {
        echo 'Gerado com sucesso';
      }*/

      //header('Location: relatorios/relatorio.pdf');

   }
  
   echo "<br><a href='index.php'>Home</a>"; 
?>
 