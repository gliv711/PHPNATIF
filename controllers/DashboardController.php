<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/LogModel.php';

function dashboardIndex() {
    requireAdmin(); 
    
    global $pdo;
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM CARNET");
    $totalContacts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("
        SELECT DATE_FORMAT(created_at, '%Y-%m') as mois, COUNT(*) as nombre 
        FROM CARNET 
        WHERE created_at IS NOT NULL 
        GROUP BY mois 
        ORDER BY mois DESC 
        LIMIT 6
    ");
    $contactsParMois = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $moisLabels = json_encode(array_column($contactsParMois, 'mois'));
    $moisData = json_encode(array_column($contactsParMois, 'nombre'));
    
    $stmt = $pdo->query("
        SELECT UPPER(SUBSTRING(nom, 1, 1)) as lettre, COUNT(*) as total 
        FROM CARNET 
        GROUP BY lettre 
        ORDER BY total DESC 
        LIMIT 10
    ");
    $topLettres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $lettresLabels = json_encode(array_column($topLettres, 'lettre'));
    $lettresData = json_encode(array_column($topLettres, 'total'));
    
    $recentLogs = getRecentLogs($pdo, 20);
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM CARNET WHERE photo IS NOT NULL AND photo != ''");
    $totalPhotos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    include __DIR__ . '/../views/dashboard/index.php';
}
?>