<?php
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Aqui você poderia inserir os dados no banco de dados
echo "Cadastro realizado com sucesso! Nome de usuário: $username.";
header("Location: indexLogin.php");
    exit();
?>
