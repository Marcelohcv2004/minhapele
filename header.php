<?php
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <!-- Meta tags essenciais -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Ícones e Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS Principal -->
    <link rel="stylesheet" href="style.css">
    <title>Minha Pele em Cores</title>
</head>
<body>
    <header id="cabecalho">
        <h2>Pele</h2>
        <nav id="nav-bar">
            <div class="menu-toggle" id="mobile-menu">
                <i class="fa-solid fa-bars"></i>
            </div>
            <ul class="nav-list">
                <li><a href="index.html">Início</a></li>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="create_debate.php">Criar Debate</a></li>
                        <li><a href="admin_users.php">Gerenciar Usuários</a></li>
                    <?php endif; ?>
                    <li><a href="index.php">Debates</a></li>
                    <li><a href="messages.php">Mensagens</a></li>
                    <li><a href="logout.php">Sair</a></li>
                <?php else: ?>
                    <li><a href="login.php">Entrar</a></li>
                    <li><a href="register.php">Registrar</a></li>
                <?php endif; ?>
                <i class="fa-solid fa-bars"></i>
                <i class="fa-solid fa-x"></i>
            </ul>
        </nav>
    </header>
    <main id="conteiner">
