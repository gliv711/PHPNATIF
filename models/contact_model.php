<?php
require_once __DIR__ . '/../config/db.php';

function getAllContacts($pdo, $search = '', $limit = null, $offset = null) {
    $sql = "SELECT * FROM CARNET";
    $params = [];
    
    if (!empty($search)) {
        $sql .= " WHERE nom LIKE ? OR prenom LIKE ? OR telephone LIKE ? OR email LIKE ?";
        $searchParam = "%$search%";
        $params = [$searchParam, $searchParam, $searchParam, $searchParam];
    }
    
    $sql .= " ORDER BY id DESC";
    
    if ($limit !== null && $offset !== null) {
        $sql .= " LIMIT ? OFFSET ?";
    }
    
    $stmt = $pdo->prepare($sql);
    
    foreach ($params as $i => $val) {
        $stmt->bindValue($i + 1, $val, PDO::PARAM_STR);
    }
    
    if ($limit !== null && $offset !== null) {
        // Les index continuent : count($params) + 1, +2
        $stmt->bindValue(count($params) + 1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countContacts($pdo, $search = '') {
    $sql = "SELECT COUNT(*) FROM CARNET";
    if (!empty($search)) {
        $sql .= " WHERE nom LIKE ? OR prenom LIKE ? OR telephone LIKE ? OR email LIKE ?";
        $searchParam = "%$search%";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$searchParam, $searchParam, $searchParam, $searchParam]);
    } else {
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchColumn();
}

function getContactById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM CARNET WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertContact($pdo, $nom, $prenom, $telephone, $email, $adresse, $photo = null) {
    if ($photo) {
        $stmt = $pdo->prepare("INSERT INTO CARNET (nom, prenom, telephone, email, adresse, photo) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $photo]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO CARNET (nom, prenom, telephone, email, adresse) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $prenom, $telephone, $email, $adresse]);
    }
}

function updateContact($pdo, $id, $nom, $prenom, $telephone, $email, $adresse, $photo = null) {
    if ($photo) {
        $stmt = $pdo->prepare("UPDATE CARNET SET nom=?, prenom=?, telephone=?, email=?, adresse=?, photo=? WHERE id=?");
        return $stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $photo, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE CARNET SET nom=?, prenom=?, telephone=?, email=?, adresse=? WHERE id=?");
        return $stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $id]);
    }
}

function deletePhotoFile($photoPath) {
    if ($photoPath && file_exists($photoPath)) {
        unlink($photoPath);
    }
}
?>