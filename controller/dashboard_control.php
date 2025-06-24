<?php 
    require __DIR__ . '/../config/database.php';
    require __DIR__ . '/../model/dashboard_admin_model.php';
    
    session_start();
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];

    $rooms = get_kost_info($user_id, $conn);
    $revenues = get_kost_revenues($user_id, $conn);

    $month_year = date('Y-m');
    $activities = get_recent_activity($user_id, $conn, $month_year);
?>