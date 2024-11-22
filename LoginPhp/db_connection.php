<?php
// Configurações do banco de dados
$servername = "localhost"; // Ou 127.0.0.1
$username = "root"; // usuário do MySQL
$password = "1207"; // senha do MySQL 
$dbname = "devNotesDB";

// criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}
?>
