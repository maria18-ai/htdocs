<?php
session_start();
include 'db_connection.php'; // Arquivo com a conexão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Consulta ao banco de dados
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verifica a senha usando password_verify
            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $email;
                header("Location: /devNotesPHP/index.php");
                exit();
            } else {
                echo "E-mail ou senha incorretos.";
                echo "<br><a href='login.php'>Tentar novamente</a>";
            }
        } else {
            echo "E-mail ou senha incorretos.";
            echo "<br><a href='login.php'>Tentar novamente</a>";
        }

        $stmt->close(); // Fecha o statement após a execução
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }
} else {
    header("Location: login.php");
    exit();
}
?>
