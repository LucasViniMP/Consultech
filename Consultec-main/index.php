<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultec - Sistema Médico</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f7f9fc;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: #fff;
            border-bottom: 1px solid #eee;
        }
        .navbar .logo {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }
        .navbar nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }
        .navbar nav a.highlight {
            background: #2563eb;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
        }
        .hero {
            text-align: center;
            padding: 100px 20px;
        }
        .user-info {
            background: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <header class="navbar">
        <a href="index.php" class="logo">Consultec</a>
        <nav>
            <a href="index.php">Home</a>
            <a href="dashboard-admin.php">Dashboard Admin</a>
            <a href="dashboard-medico.php">Dashboard Médico</a>
            <a href="auth/login.php" class="highlight">Login</a>
        </nav>
    </header>

    <div class="hero">
        <h1>Bem-vindo ao Consultec</h1>
        <p>Sistema de agendamento médico</p>
        
        <?php if (isset($_SESSION['usuario'])): ?>
            <div class="user-info">
                <h3>Olá, <?php echo $_SESSION['usuario']['nome']; ?>!</h3>
                <p>Você está logado como: <?php echo $_SESSION['usuario']['tipo']; ?></p>
                <a href="auth/logout.php">Sair</a>
            </div>
        <?php else: ?>
            <p><a href="auth/login.php">Faça login</a> para acessar o sistema</p>
        <?php endif; ?>
    </div>
</body>
</html>