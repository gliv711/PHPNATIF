<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../models/contact_model.php';
require_once __DIR__ . '/../controllers/AuthController.php'; 

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
        if (empty($errors)) {
            if (insertContact($pdo, $nom, $prenom, $telephone, $email, $adresse)) {
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
        if (empty($errors)) {
            if (updateContact($pdo, $id, $nom, $prenom, $telephone, $email, $adresse)) {
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
        deleteContact($pdo, $id);
        setFlashMessage('success', 'Contact supprimé.');
    }
    header('Location: index.php?action=list');
    exit();
}
?>