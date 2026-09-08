<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

try {
    $db = new PDO('sqlite:tecsolidario.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        senha TEXT NOT NULL
    )");

    $erro = '';
    $sucesso = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['registrar'])) {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

            try {
                $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                $stmt->execute([$nome, $email, $senha]);
                $sucesso = "Cadastro realizado! Agora faça o seu login.";
            } catch (PDOException $e) {
                $erro = "Este e-mail já está cadastrado.";
            }
        } 
        
        if (isset($_POST['logar'])) {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                header('Location: index.php');
                exit;
            } else {
                $erro = "E-mail ou senha incorretos.";
            }
        }
    }
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acessar - TecSolidario</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-container { max-width: 400px; margin: 50px auto; padding: 20px; background: #f9f9f9; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: Arial, sans-serif; }
        .auth-box h2 { margin-bottom: 20px; text-align: center; color: #333; }
        .auth-box input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .auth-box button { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .auth-box button:hover { background-color: #218838; }
        .mensagem { padding: 10px; margin: 10px 0; text-align: center; border-radius: 4px; }
        .erro { background-color: #f8d7da; color: #721c24; }
        .sucesso { background-color: #d4edda; color: #155724; }
        .toggle-link { text-align: center; margin-top: 15px; display: block; color: #007bff; text-decoration: none; cursor: pointer; }
    </style>
</head>
<body>

    <div class="auth-container">
        <?php if ($erro): ?> <div class="mensagem erro"><?= $erro ?></div> <?php endif; ?>
        <?php if ($sucesso): ?> <div class="mensagem sucesso"><?= $sucesso ?></div> <?php endif; ?>

        <!-- Formulário de Login -->
        <div id="login-box" class="auth-box">
            <h2>Entrar no TecSolidario</h2>
            <form method="POST">
                <input type="email" name="email" placeholder="Seu E-mail" required>
                <input type="password" name="senha" placeholder="Sua Senha" required>
                <button type="submit" name="logar">Entrar</button>
            </form>
            <a class="toggle-link" onclick="alternarAbas()">Não tem uma conta? Cadastre-se</a>
        </div>

        <!-- Formulário de Cadastro -->
        <div id="cadastro-box" class="auth-box" style="display: none;">
            <h2>Criar Nova Conta</h2>
            <form method="POST">
                <input type="text" name="nome" placeholder="Seu Nome Completo" required>
                <input type="email" name="email" placeholder="Seu E-mail" required>
                <input type="password" name="senha" placeholder="Crie uma Senha" required>
                <button type="submit" name="registrar">Criar Conta</button>
            </form>
            <a class="toggle-link" onclick="alternarAbas()">Já tem uma conta? Faça Login</a>
        </div>
    </div>

    <script>
        function alternarAbas() {
            var loginBox = document.getElementById('login-box');
            var cadastroBox = document.getElementById('cadastro-box');
            if (loginBox.style.display === 'none') {
                loginBox.style.display = 'block';
                cadastroBox.style.display = 'none';
            } else {
                loginBox.style.display = 'none';
                cadastroBox.style.display = 'block';
            }
        }
    </script>
</body>
</html>
