<?php
require_once __DIR__ . '/../config/db.php';

function findUserByEmail($pdo, $email) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function findUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createUser($pdo, $nom, $email, $password, $role = 'user') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nom, $email, $hashed, $role]);
}

function updateUserProfile($pdo, $id, $nom, $email) {
    $stmt = $pdo->prepare("UPDATE users SET nom = ?, email = ? WHERE id = ?");
    return $stmt->execute([$nom, $email, $id]);
}

function updateUserPassword($pdo, $id, $newPassword) {
    $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$hashed, $id]);
}
?>