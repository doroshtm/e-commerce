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

  function startSession() {
    if (isset($_COOKIE['loginCookie'])) {
        session_id($_COOKIE['loginCookie']);
    }
    session_start();
    return session_id();
  }

  function login ($email, $password, &$isAdmin)  
  {
   $isAdmin = ($email == 'marcelo.peres@unesp.br' and 
                  $password == '12345');

   return true;

  }
?>