<?php
include 'db_connection.php';

// verifica se o método da requisição é POST (indicando que o formulário foi enviado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

     // verifica se todos os campos foram preenchidos
    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashed_password = md5($password);

        // prepara a consulta SQL para inserir os dados no banco
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

         // executa a consulta e verifica se a inserção foi bem-sucedida
        if ($stmt->execute()) {
            header("Location: indexLogin.php");
            exit();
        } else {
            echo "Erro ao cadastrar usuário: " . $conn->error;
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}
?>
