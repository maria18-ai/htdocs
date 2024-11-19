<?php
// Configurações do banco de dados
$servername = "localhost"; // Ou 127.0.0.1
$username = "root"; // Seu usuário do MySQL
$password = "1207"; // Sua senha do MySQL (deixe vazio se você não configurou uma senha no XAMPP)
$dbname = "devNotesDB";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
?>
