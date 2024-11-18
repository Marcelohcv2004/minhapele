<?php
include 'header.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['to_user_id'])) {
    header('Location: admin_users.php');
    exit;
}

$to_user_id = $_GET['to_user_id'];

// Obter informações do usuário destinatário
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$to_user_id]);
$to_user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$to_user) {
    header('Location: admin_users.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = trim($_POST['subject']);
    $content = trim($_POST['content']);

    if (empty($content)) {
        $error = "Por favor, escreva uma mensagem.";
    } else {
        // Enviar mensagem
        $stmt = $pdo->prepare('INSERT INTO messages (sender_id, receiver_id, subject, content) VALUES (?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $to_user_id, $subject, $content]);
        $success = "Mensagem enviada com sucesso!";
    }
}
?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Enviar Mensagem para <?php echo escape($to_user['username']); ?></h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo escape($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo escape($success); ?></p>
        <?php endif; ?>
        <form action="send_message.php?to_user_id=<?php echo $to_user_id; ?>" method="post">
            <label for="subject">Assunto:</label>
            <input type="text" name="subject" id="subject">

            <label for="content">Mensagem:</label>
            <textarea name="content" id="content" required></textarea>

            <input type="submit" value="Enviar" class="botao">
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
