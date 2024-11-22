<?php
session_start();
// inclui o arquivo de conexão com o banco de dados 
include 'db_connection.php';

// verifica se o método da requisição é POST (indicando que o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $hashed_password = md5($password);

    // prepara a consulta SQL para buscar o usuário com o e-mail e senha fornecidos
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();

    // verifica se existe pelo menos um registro correspondente
    if ($result->num_rows > 0) {
        $_SESSION['email'] = $email;
        header("Location: /devNotesPHP/index.php");
        exit();
    } else {
        echo "E-mail ou senha incorretos.";
        echo "<br><a href='indexLogin.php'>Tentar novamente</a>";
    }
}
?>
