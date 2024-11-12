<?php
// Definindo o caminho para o arquivo JSON
$notesFile = 'notes.json';

// Verifica se o arquivo existe, se não, cria um arquivo vazio
if (!file_exists($notesFile)) {
    file_put_contents($notesFile, json_encode([]));
}

// Função para carregar as notas do arquivo JSON
function getNotes() {
    global $notesFile;
    return json_decode(file_get_contents($notesFile), true) ?: [];
}

// Função para salvar as notas no arquivo JSON
function saveNotes($notes) {
    global $notesFile;
    file_put_contents($notesFile, json_encode($notes, JSON_PRETTY_PRINT));
}

// Adiciona uma nova nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $notes = getNotes();
    $newNote = [
        'id' => rand(1, 5000), // Gerando um ID aleatório
        'content' => $_POST['content'],
        'fixed' => false
    ];
    $notes[] = $newNote;
    saveNotes($notes);
    exit; // Encerra o script após adicionar a nota
}

// Atualiza o conteúdo de uma nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $notes = getNotes();
    foreach ($notes as &$note) {
        if ($note['id'] == $_POST['id']) {
            $note['content'] = $_POST['content'];
            break;
        }
    }
    saveNotes($notes);
    exit;
}

// Função para remover uma nota
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $notes = getNotes();
    $notes = array_filter($notes, fn($note) => $note['id'] != $_POST['id']);
    saveNotes($notes);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes</title>
    
</head>
<body>
    <header>
        <h1>Notas</h1>
        <div id="search-container">
            <input type="text" id="search-input" placeholder="Busque por uma nota">
        </div>
        <div id="add-note-container">
            <input type="text" id="note-content" placeholder="O que deseja anotar">
            <button class="add-note">Adicionar</button>
        </div>
    </header>

    <div id="notes-container">
        <?php
        $notes = getNotes();
        if (count($notes) > 0) {
            foreach ($notes as $note):
        ?>
        <div class="note" data-id="<?= $note['id'] ?>" id="note-<?= $note['id'] ?>">
            <textarea><?= htmlspecialchars($note['content']) ?></textarea>
            <button class="pin" onclick="toggleFixed(<?= $note['id'] ?>)">Fixar</button>
            <button class="delete" onclick="deleteNote(<?= $note['id'] ?>)">Excluir</button>
        </div>
        <?php endforeach; } ?>
    </div>

    <script>
        // Função para adicionar uma nova nota
        document.querySelector('.add-note').addEventListener('click', function() {
            const content = document.querySelector('#note-content').value;
            if (content) {
                fetch('index.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'add',
                        content: content
                    })
                }).then(() => location.reload());
            }
        });

        // Função para fixar/desfixar uma nota
        function toggleFixed(id) {
            const note = document.querySelector(`#note-${id}`);
            note.classList.toggle('fixed');
            updateNote(id, note.querySelector('textarea').value);
        }

        // Função para atualizar uma nota
        function updateNote(id, content) {
            fetch('index.php', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'update',
                    id: id,
                    content: content
                })
            });
        }

        // Função para excluir uma nota
        function deleteNote(id) {
            if (confirm('Você tem certeza que deseja excluir esta nota?')) {
                fetch('index.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        action: 'delete',
                        id: id
                    })
                }).then(() => location.reload());
            }
        }
    </script>
</body>
</html>
