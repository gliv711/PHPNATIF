<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/contact_model.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/LogModel.php';

// Fonction utilitaire pour les messages flash (inchangée)
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function validateContact($nom, $prenom, $telephone, $email, $adresse) {
    $errors = [];
    if (empty($nom)) $errors[] = "Le nom est requis.";
    if (empty($prenom)) $errors[] = "Le prénom est requis.";
    if (empty($telephone)) $errors[] = "Le téléphone est requis.";
    elseif (!preg_match('/^[0-9+\-\s]{8,20}$/', $telephone)) $errors[] = "Téléphone invalide (8 à 20 chiffres, +, -, espaces).";
    if (empty($email)) $errors[] = "L'email est requis.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide.";
    if (empty($adresse)) $errors[] = "L'adresse est requise.";
    return $errors;
}

function uploadPhoto($file) {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed)) {
        return false;
    }
    
    $maxSize = 2 * 1024 * 1024; // 2MB
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $uploadDir = __DIR__ . '/../public/uploads/';
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        return 'uploads/' . $filename;
    }
    
    return false;
}

function listContacts() {
    global $pdo;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 5;
    $offset = ($page - 1) * $perPage;
    
    $totalContacts = countContacts($pdo, $search);
    $totalPages = ceil($totalContacts / $perPage);
    $contacts = getAllContacts($pdo, $search, $perPage, $offset);
    $isAdmin = isAdmin();
    
    include __DIR__ . '/../views/contact/index.php';
}

function showCreateForm() {
    include __DIR__ . '/../views/contact/create.php';
}

function storeContact() {
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $telephone = trim($_POST['telephone']);
        $email = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        
        $errors = validateContact($nom, $prenom, $telephone, $email, $adresse);
        
        $photoPath = uploadPhoto($_FILES['photo']);
        if ($photoPath === false) {
            $errors[] = "Photo invalide (JPEG/PNG/GIF, max 2MB).";
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("INSERT INTO CARNET (nom, prenom, telephone, email, adresse, photo) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $photoPath])) {
                // Log l'action
                addLog($pdo, $_SESSION['user_id'], $_SESSION['user_nom'], 'CREATE', "Ajout contact: $nom $prenom");
                setFlashMessage('success', 'Contact ajouté avec succès.');
                header('Location: index.php?action=list');
                exit();
            } else {
                setFlashMessage('danger', 'Erreur lors de l\'ajout.');
            }
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: index.php?action=create');
            exit();
        }
    }
}

function showEditForm() {
    global $pdo;
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header('Location: index.php?action=list');
        exit();
    }
    $contact = getContactById($pdo, $id);
    include __DIR__ . '/../views/contact/edit.php';
}

function updateContactAction() {
    global $pdo;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $telephone = trim($_POST['telephone']);
        $email = trim($_POST['email']);
        $adresse = trim($_POST['adresse']);
        
        $errors = validateContact($nom, $prenom, $telephone, $email, $adresse);
        
        $oldContact = getContactById($pdo, $id);
        $photoPath = $oldContact['photo'];
        
        if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $newPhoto = uploadPhoto($_FILES['photo']);
            if ($newPhoto === false) {
                $errors[] = "Photo invalide (JPEG/PNG/GIF, max 2MB).";
            } else {
                // Supprimer l'ancienne photo
                if ($photoPath && file_exists(__DIR__ . '/../public/' . $photoPath)) {
                    unlink(__DIR__ . '/../public/' . $photoPath);
                }
                $photoPath = $newPhoto;
            }
        }
        
        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE CARNET SET nom = ?, prenom = ?, telephone = ?, email = ?, adresse = ?, photo = ? WHERE id = ?");
            if ($stmt->execute([$nom, $prenom, $telephone, $email, $adresse, $photoPath, $id])) {
                addLog($pdo, $_SESSION['user_id'], $_SESSION['user_nom'], 'UPDATE', "Modifié contact: $nom $prenom (ID: $id)");
                setFlashMessage('success', 'Contact modifié avec succès.');
                header('Location: index.php?action=list');
                exit();
            } else {
                setFlashMessage('danger', 'Erreur lors de la modification.');
            }
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header("Location: index.php?action=edit&id=$id");
            exit();
        }
    }
}

function deleteContactAction() {
    global $pdo;
    $id = $_GET['id'] ?? null;
    if ($id) {
        $contact = getContactById($pdo, $id);
        if ($contact && $contact['photo'] && file_exists(__DIR__ . '/../public/' . $contact['photo'])) {
            unlink(__DIR__ . '/../public/' . $contact['photo']);
        }
        deleteContact($pdo, $id);
        addLog($pdo, $_SESSION['user_id'], $_SESSION['user_nom'], 'DELETE', "Supprimé contact: {$contact['nom']} {$contact['prenom']} (ID: $id)");
        setFlashMessage('success', 'Contact supprimé.');
    }
    header('Location: index.php?action=list');
    exit();
}
?>