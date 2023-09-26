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

  }

  function defineCookie($name, $value, $time) 
  {
   echo "Cookie: $name Valor: $value";  
   setcookie($name, $value, time() + $time); 
  }
?>