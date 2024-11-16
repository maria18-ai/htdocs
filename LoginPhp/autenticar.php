<?php
session_start();

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $email = $_POST['email'] ?? ''; // Nome do campo 'email'
    $password = $_POST['password'] ?? ''; // Nome do campo 'password'

    // Credenciais simuladas
    $email_correto = 'usuario@email.com';
    $senha_correta = 'usuario@email.com';

    // Valida as credenciais
    if ($email === $email_correto && $password === $senha_correta) {
        // Salva o email do usuário na sessão
        $_SESSION['email'] = $email;

        // Redireciona para a página principal
        header("Location: /devNotesPHP/index.php");
        exit();
    } else {
        // Exibe mensagem de erro e link para voltar ao login
        echo "E-mail ou senha incorretos.";
        echo "<br><a href='login.php'>Tentar novamente</a>";
    }
} else {
    // Caso a página seja acessada diretamente, redireciona para o login
    header("Location: login.php");
    exit();
}
?>
