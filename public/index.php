<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/contact_controller.php';

$action = $_GET['action'] ?? 'list';

$publicActions = ['login', 'doLogin', 'logout'];

if (!in_array($action, $publicActions)) {
    requireAuth(); 
}

switch ($action) {
    // Authentification
    case 'login':
        showLoginForm();
        break;
    case 'doLogin':
        login();
        break;
    case 'logout':
        logout();
        break;
    
    case 'list':
        listContacts();
        break;
    case 'create':
        showCreateForm();
        break;
    case 'store':
        storeContact();
        break;
    case 'edit':
        showEditForm();
        break;
    case 'update':
        updateContactAction();
        break;
    case 'delete':
        requireAdmin(); 
        deleteContactAction();
        break;
    default:
        listContacts();
        break;
}
?>