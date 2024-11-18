<?php
include 'header.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$debate_id = $_GET['id'];

// Handle deletion of comments by admin
if (isLoggedIn() && isAdmin() && isset($_GET['delete_post_id'])) {
    $delete_post_id = $_GET['delete_post_id'];

    // Delete the post
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$delete_post_id]);

    // Redirect to the same debate page
    header("Location: debate.php?id=$debate_id");
    exit;
}

// Get the debate
$stmt = $pdo->prepare('SELECT * FROM debates WHERE id = ?');
$stmt->execute([$debate_id]);
$debate = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$debate) {
    header('Location: index.php');
    exit;
}

// Get the posts
$stmt = $pdo->prepare('
    SELECT posts.*, users.username, users.id as user_id FROM posts
    JOIN users ON posts.user_id = users.id
    WHERE debate_id = ?
    ORDER BY created_at ASC
');
$stmt->execute([$debate_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }

    $content = trim($_POST['content']);
    if (empty($content)) {
        $error = "Por favor, escreva sua experiência.";
    } else {
        $stmt = $pdo->prepare('INSERT INTO posts (debate_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->execute([$debate_id, $_SESSION['user_id'], $content]);
        header("Location: debate.php?id=$debate_id");
        exit;
    }
}
?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1><?php echo escape($debate['title']); ?></h1>
        <p><?php echo nl2br(escape($debate['description'])); ?></p>

        <h2></h2>
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <p>
                    <?php if (isAdmin()): ?>
                        <strong><?php echo escape($post['username']); ?> (ID: <?php echo $post['user_id']; ?>)</strong>
                    <?php else: ?>
                        <strong><?php echo escape($post['username']); ?></strong>
                    <?php endif; ?>
                    em <?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?>
                </p>
                <p><?php echo nl2br(escape($post['content'])); ?></p>
                <?php if (isAdmin()): ?>
                    <a href="debate.php?id=<?php echo $debate_id; ?>&delete_post_id=<?php echo $post['id']; ?>" class="botao botao-excluir" onclick="return confirm('Tem certeza que deseja excluir este comentário?')">Excluir</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (isLoggedIn()): ?>
            <h2>Compartilhe sua experiência</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo escape($error); ?></p>
            <?php endif; ?>
            <form action="debate.php?id=<?php echo $debate_id; ?>" method="post">
                <label for="content">Sua Vivência:</label>
                <textarea name="content" id="content" required></textarea>
                <input type="submit" value="Enviar" class="botao">
            </form>
        <?php else: ?>
            <p>Você precisa <a href="login.php">entrar</a> para compartilhar sua experiência.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
