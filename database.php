<?php
try {
    $db = new PDO('sqlite:tecsolidario.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        senha TEXT NOT NULL
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT,
        preco REAL DEFAULT 0,
        tipo TEXT DEFAULT 'venda'
    )");

    $verificar = $db->query("SELECT COUNT(*) FROM produtos");
    if ($verificar->fetchColumn() == 0) {
        $db->exec("INSERT INTO produtos (nome, descricao, preco, tipo) VALUES 
            ('Placa de Vídeo GTX 1660 Super', '6GB GDDR6, perfeita para jogos e estudos.', 850.00, 'venda'),
            ('Memória RAM Kingston 8GB DDR4', 'Doação para quem precisa montar um PC estudantil.', 0.00, 'doacao'),
            ('Fonte Corsair CV550 550W', 'Selo 80 Plus Bronze. Funcionando perfeitamente.', 250.00, 'venda'),
            ('Processador Intel Core i3 10a Gen', 'Acompanha cooler original, ideal para escritório.', 0.00, 'doacao')
        ");
    }
} catch (PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}
