<?php
include 'db_connection.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validação básica
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Criptografa a senha
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insere o usuário no banco de dados
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            if ($stmt->execute()) {
                // Redireciona para o login
                header("Location: indexLogin.php");
                exit();
            } else {
                echo "Erro ao cadastrar usuário: " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Erro na preparação da consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
} else {
    header("Location: cadastro.php"); // Redireciona para o formulário de cadastro
    exit();
}
?>
