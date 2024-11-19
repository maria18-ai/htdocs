<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Cadastro</h2>
    <form action="process_cadastro.php" method="POST">
        <input type="text" name="username" placeholder="Nome de Usuário" required>
        <input type="email" name="email" placeholder="E-mail" required>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
    </form>

        <p>Já tem uma conta? <a href="indexLogin.php">Faça login</a></p>
    </div>
</body>
</html>