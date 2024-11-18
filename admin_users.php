<?php
include 'header.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: index.php');
    exit;
}

// Banir ou desbanir usuário
if (isset($_GET['ban_user_id'])) {
    $user_id = $_GET['ban_user_id'];
    $stmt = $pdo->prepare('UPDATE users SET is_banned = 1 WHERE id = ?');
    $stmt->execute([$user_id]);
    header('Location: admin_users.php');
    exit;
}

if (isset($_GET['unban_user_id'])) {
    $user_id = $_GET['unban_user_id'];
    $stmt = $pdo->prepare('UPDATE users SET is_banned = 0 WHERE id = ?');
    $stmt->execute([$user_id]);
    header('Location: admin_users.php');
    exit;
}

// Funcionalidade de pesquisa
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $search_query = '%' . $search . '%';
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? OR username LIKE ? OR email LIKE ? ORDER BY registration_date DESC');
    $stmt->execute([$search, $search_query, $search_query]);
} else {
    // Buscar todos os usuários
    $stmt = $pdo->query('SELECT * FROM users ORDER BY registration_date DESC');
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section id="secao-1">
    <div class="itens-secao-1">
        <h1>Gerenciar Usuários</h1>
        <form method="get" action="admin_users.php" class="form-pesquisa">
            <input type="text" name="search" placeholder="Pesquisar por ID, Nome ou Email" value="<?php echo escape($search); ?>">
            <input type="submit" value="Pesquisar" class="botao botao-criar">
            <?php if ($search != ''): ?>
                <a href="admin_users.php" class="botao botao-excluir">Limpar Pesquisa</a>
            <?php endif; ?>
        </form>
        <table class="tabela-usuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome de Usuário</th>
                    <th>Email</th>
                    <th>Data de Registro</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6">Nenhum usuário encontrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo escape($user['id']); ?></td>
                            <td><?php echo escape($user['username']); ?></td>
                            <td><?php echo escape($user['email']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user['registration_date'])); ?></td>
                            <td><?php echo $user['is_banned'] ? 'Banido' : 'Ativo'; ?></td>
                            <td>
                            <?php if ($user['is_banned']): ?>
                                <a href="admin_users.php?unban_user_id=<?php echo $user['id']; ?>" class="botao">
                                    <i class="fa fa-unlock"></i> Desbanir
                                </a>
                            <?php else: ?>
                                <a href="admin_users.php?ban_user_id=<?php echo $user['id']; ?>" class="botao botao-banir" onclick="return confirm('Tem certeza que deseja banir este usuário?')">
                                     <i class="fa fa-ban"></i> Banir
                                </a>
                            <?php endif; ?>
                                <a href="send_message.php?to_user_id=<?php echo $user['id']; ?>" class="botao botao-mensagem">
                                     <i class="fa fa-envelope"></i> Enviar Mensagem
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'footer.php'; ?>
