<?php
require_once __DIR__ . '/../config/db.php';

function addLog($pdo, $userId, $userNom, $action, $details = null) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $stmt = $pdo->prepare("INSERT INTO logs (user_id, user_nom, action, details, ip_address) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$userId, $userNom, $action, $details, $ip]);
}

function getRecentLogs($pdo, $limit = 20) {
    $stmt = $pdo->prepare("SELECT * FROM logs ORDER BY created_at DESC LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllLogs($pdo) {
    $stmt = $pdo->query("SELECT * FROM logs ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>