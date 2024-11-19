<?php
include 'db_connection.php';

if ($conn) {
    echo "Conexão com o banco de dados estabelecida com sucesso!";
} else {
    echo "Erro na conexão.";
}
?>
