<?php
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    // Verifica se o usuário existe
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
    $stmt->execute([$username_or_email, $username_or_email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_banned'] == 1) {
            $error = "Sua conta está banida.";
        } else {
            // Login bem-sucedido
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header('Location: index.php');
            exit;
        }
    } else {
        $error = "Nome de usuário ou senha incorretos.";
    }
}
?>

<?php include 'header.php'; ?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Entrar</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo escape($error); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username_or_email">Nome de Usuário ou Email:</label>
            <input type="text" name="username_or_email" id="username_or_email" required>

            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Entrar" class="botao">
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
