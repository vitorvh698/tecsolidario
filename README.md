# TecSolidário

Marketplace em PHP + SQLite + HTML/CSS + JavaScript.

## Requisitos
- PHP 8+ com extensão PDO SQLite habilitada.

## Como executar
1. Extraia a pasta.
2. Abra um terminal dentro dela.
3. Execute: `php -S localhost:8000`
4. Acesse: `http://localhost:8000`

O banco `tecsolidario.sqlite` é criado automaticamente na primeira execução.

## O que já funciona
- Catálogo de peças
- Busca e filtro por categoria
- Carrinho persistido no navegador
- Cadastro de doações salvo no SQLite
- Layout responsivo
- API PHP em `api.php`

O checkout é demonstrativo; para pagamentos reais, integre um gateway como Mercado Pago ou Stripe no backend.
