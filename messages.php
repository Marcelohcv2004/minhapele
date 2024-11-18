<?php
include 'header.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Obter mensagens recebidas pelo usuário
$stmt = $pdo->prepare('SELECT messages.*, users.username AS sender_username FROM messages JOIN users ON messages.sender_id = users.id WHERE receiver_id = ? ORDER BY sent_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Suas Mensagens</h1>
        <?php if (empty($messages)): ?>
            <p>Você não tem mensagens.</p>
        <?php else: ?>
            <?php foreach ($messages as $message): ?>
                <div class="mensagem">
                    <p><strong>De:</strong> <?php echo escape($message['sender_username']); ?> em <?php echo date('d/m/Y H:i', strtotime($message['sent_at'])); ?></p>
                    <p><strong>Assunto:</strong> <?php echo escape($message['subject']); ?></p>
                    <p><?php echo nl2br(escape($message['content'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>
