<?php
// index.php
include 'header.php';

// Handle deletion of debates by admin
if (isLoggedIn() && isAdmin() && isset($_GET['delete_debate_id'])) {
    $delete_debate_id = $_GET['delete_debate_id'];

    // Delete the debate
    $stmt = $pdo->prepare('DELETE FROM debates WHERE id = ?');
    $stmt->execute([$delete_debate_id]);

    // Redirect to index.php
    header('Location: index.php');
    exit;
}

// Fetch all debates
$stmt = $pdo->query('SELECT * FROM debates ORDER BY created_at DESC');
$debates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Debates</h1>
        <?php if (isAdmin()): ?>
            <a href="create_debate.php" class="botao botao-criar">Criar Novo Debate</a>
        <?php endif; ?>

        
        <ul>
            <?php foreach ($debates as $debate): ?>
                <li>
                    <a href="debate.php?id=<?php echo $debate['id']; ?>">
                        <?php echo escape($debate['title']); ?>
                    </a>
                    <p><?php echo escape($debate['description']); ?></p>
                    <?php if (isAdmin()): ?>
                        <a href="index.php?delete_debate_id=<?php echo $debate['id']; ?>" class="botao botao-excluir" onclick="return confirm('Tem certeza que deseja excluir este debate?')">Excluir</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<?php include 'footer.php'; ?>
