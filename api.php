<?php
header('Content-Type: application/json');
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $db->query("SELECT * FROM produtos");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode(['erro' => $e->getMessage()]);
    }
}
