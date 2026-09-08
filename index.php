<?php
require_once 'database.php';
try {
    $resultado = $db->query("SELECT * FROM produtos ORDER BY id DESC");
    $pecas = $resultado->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar peças: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecSolidario - Venda e Doação de Peças</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>💻 TecSolidario</h1>
            <p>Conectando quem precisa de tecnologia com quem quer ajudar</p>
        </div>
    </header>

    <main class="container">
        <h2>Peças Disponíveis</h2>
        <div class="lista-produtos">
            <?php if (empty($pecas)): ?>
                <p class="aviso">Nenhuma peça cadastrada no momento.</p>
            <?php else: ?>
                <?php foreach ($pecas as $peca): ?>
                    <div class="card-produto <?= $peca['tipo'] === 'doacao' ? 'card-doacao' : 'card-venda' ?>">
                        <h3><?= htmlspecialchars($peca['nome']) ?></h3>
                        <p><?= htmlspecialchars($peca['descricao'] ?? '') ?></p>
                        <span class="tag-tipo">
                            <?= $peca['tipo'] === 'doacao' ? '🎁 DOAÇÃO GRATUITA' : '💰 VENDA: R$ ' . number_format($peca['preco'], 2, ',', '.') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    <script src="script.js"></script>
</body>
</html>
