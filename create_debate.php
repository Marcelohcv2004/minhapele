<?php
require_once 'functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($description)) {
        $error = "Por favor, preencha todos os campos.";
    } else {
        $stmt = $pdo->prepare('INSERT INTO debates (title, description) VALUES (?, ?)');
        $stmt->execute([$title, $description]);
        header('Location: index.php');
        exit;
    }
}
?>

<?php include 'header.php'; ?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Criar Novo Debate</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo escape($error); ?></p>
        <?php endif; ?>
        <form action="create_debate.php" method="post">
            <label for="title">Título:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Descrição:</label>
            <textarea name="description" id="description" required></textarea>

            <input type="submit" value="Criar" class="botao">
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
