<?php 
  function connect ($params = "") {
    if ($params == "") {
        $params="pgsql:host=pgsql.projetoscti.com.br; dbname=projetoscti24; 
                 user=projetoscti24; password=721369";
    }

    $varConn = new PDO($params);

    if (!$varConn) {
        echo "Não foi possivel conectar";
    } else { return $varConn; }
  }

  function login ($email, $password, &$isAdmin)  
  {
   $isAdmin = ($email == 'marcelo.peres@unesp.br' and 
                  $password == '12345');

   return true;

  }q

  function getUsername($cookie, $connection) {
    if ($cookie != NULL) {
        $select = $connection->prepare('select nome from usuarios where id_usuario = :id_usuario');
        $select->execute(['id_usuario' => $cookie]);
        $result = $select->fetch(PDO::FETCH_ASSOC);
        return explode(' ', $result['nome'], 2)[0];
    }
    else {
        return 'visitante';
    }
  }
?>