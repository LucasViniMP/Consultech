<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (!empty($email) && !empty($senha)) {
        $usuario = $database->autenticarUsuario($email, $senha);
        
        if ($usuario) {
            $_SESSION['usuario'] = $usuario;
            
            switch ($usuario['tipo']) {
                case 'admin':
                case 'super':
                    header('Location: ../dashboard-admin.php');
                    break;
                case 'medico':
                    header('Location: ../dashboard-medico.php');
                    break;
                default:
                    header('Location: ../index.php');
            }
            exit;
        } else {
            $erro = "Email ou senha inválidos!";
        }
    } else {
        $erro = "Por favor, preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Consultec</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f7f9fc;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .logo {
            text-align: center;
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 30px;
            color: #2563eb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 1.5px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover {
            background: #1d4ed8;
        }
        .error {
            color: #dc2626;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #fef2f2;
            border-radius: 6px;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">Consultec</div>
        
        <?php if ($erro): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit">Entrar</button>
        </form>
        
        <div class="links">
            <a href="cadastro.php">Criar conta</a> | 
            <a href="../index.php">Voltar ao site</a>
        </div>
    </div>
</body>
</html>