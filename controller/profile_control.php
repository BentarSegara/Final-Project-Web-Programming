<?php 
    require __DIR__ . '/../config/database.php';
    
    session_start();
    $user_id = $_SESSION['user_id'];

    if($_SESSION['role'] === 'owner') {
        require __DIR__ . '/../model/admin_profile_model.php';
        $users = get_admin_profile($user_id, $conn);
    }
    
    else {
        require __DIR__ . '/../model/profile_model.php';
        $users = get_user_profile($user_id, $conn);
    }
?>