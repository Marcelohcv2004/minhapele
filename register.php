<?php
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados do formulário
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validação simples
    if (empty($username) || empty($email) || empty($password)) {
        $error = "Por favor, preencha todos os campos.";
    } elseif ($password != $confirm_password) {
        $error = "As senhas não coincidem.";
    } else {
        // Verifica se o usuário ou email já existem
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();
        if ($user) {
            $error = "Nome de usuário ou email já estão em uso.";
        } else {
            // Hash da senha
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            // Insere novo usuário
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $password_hash]);
            $success = "Registro bem-sucedido! Você pode agora fazer login.";
            // Redireciona para o login após alguns segundos
            header("refresh:3;url=login.php");
        }
    }
}
?>

<?php include 'header.php'; ?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Registrar</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo escape($error); ?></p>
        <?php elseif (isset($success)): ?>
            <p class="success"><?php echo escape($success); ?></p>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="username">Nome de Usuário:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirme a Senha:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <input type="submit" value="Registrar" class="botao">
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>
