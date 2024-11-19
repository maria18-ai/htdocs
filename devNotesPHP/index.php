<?php
session_start();
include '../LoginPhp/db_connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../LoginPhp/indexLogin.php");
    exit();
}

$email = $_SESSION['email'];
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $content = $_POST['content'];
        $sql = "INSERT INTO notes (user_id, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $user_id, $content);
        $stmt->execute();
        exit;
    } elseif ($_POST['action'] === 'delete') {
        $note_id = $_POST['id'];
        $sql = "DELETE FROM notes WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $note_id, $user_id);
        $stmt->execute();
        exit;
    } elseif ($_POST['action'] === 'fix') {
        $note_id = $_POST['id'];
        $sql = "UPDATE notes SET fixed = NOT fixed WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $note_id, $user_id);
        $stmt->execute();
        exit;
    }
}

$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']); // Remover espaços extras
    $search = mysqli_real_escape_string($conn, $search); // Prevenir injeção SQL

    // Usar a pesquisa no SQL com o LIKE e os '%' apenas no momento certo
    $sql = "SELECT * FROM notes WHERE user_id = ? AND content LIKE ?";
    $search = '%' . $search . '%'; // Aqui é onde adicionamos os '%' para realizar a busca
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $search);
} else {
    $sql = "SELECT * FROM notes WHERE user_id = ? ORDER BY fixed DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$notes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Notas</title>
</head>
<body>
    <header>
        <h1>Suas Notas</h1>
        <div>
            <input type="text" id="note-content" placeholder="Nova nota">
            <button onclick="addNote()">Adicionar</button>
        </div>
        <div>
            <input type="text" id="search" placeholder="Buscar notas" value="<?= htmlspecialchars($search) ?>" oninput="searchNotes()">
        </div>
    </header>
    <div>
        <?php foreach ($notes as $note): ?>
            <div>
                <p><?= htmlspecialchars($note['content']) ?></p>
                <button onclick="deleteNote(<?= $note['id'] ?>)">Excluir</button>
                <button onclick="fixNote(<?= $note['id'] ?>)">
                    <?= $note['fixed'] ? 'Desafixar' : 'Fixar' ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        function addNote() {
            const content = document.getElementById('note-content').value;
            fetch('index.php', {
                method: 'POST',
                body: new URLSearchParams({ action: 'add', content: content })
            }).then(() => location.reload());
        }

        function deleteNote(id) {
            fetch('index.php', {
                method: 'POST',
                body: new URLSearchParams({ action: 'delete', id: id })
            }).then(() => location.reload());
        }

        function fixNote(id) {
            fetch('index.php', {
                method: 'POST',
                body: new URLSearchParams({ action: 'fix', id: id })
            }).then(() => location.reload());
        }

        function searchNotes() {
            const search = document.getElementById('search').value;
            window.location.search = `search=${encodeURIComponent(search)}`; // Passa apenas o valor codificado
        }
    </script>
</body>
</html>
