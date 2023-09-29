<?php
    session_start();
    $connection = new PDO('pgsql:dbname=projetoscti24; user=projetoscti24; password=721369; host=pgsql.projetoscti.com.br') or die("Erro!");
    $date = date('m/d/Y');
    $user = ['nome' => $_POST['name'], 'email' => $_POST['email'], 'senha' => $_POST['password'], 'telefone' => $_POST['phone'], 'cpf' => $_POST['cpf'], 'cep' => $_POST['cep'], 'endereco' => $_POST['address'], 'data_cadastro' => $date];
    echo "teste";
    $insert = $connection->prepare('insert into usuarios (id_usuario, nome, email, senha, telefone, cpf, cep, endereco, data_cadastro) values (DEFAULT, :nome, :email, :senha, :telefone, :cpf, :cep, :endereco, :data_cadastro)');
    $insert->execute($user);
    setcookie('loginCookie', $user['email'], time() + 1209600);
    header('Location: ./');
?>