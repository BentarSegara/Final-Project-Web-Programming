<?php 
    session_start();
    require_once '../config/database.php';

    if (isset($_POST['submit'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $tipe_user =  $_POST['tipe_user'];
    
        $querry = $conn->query("SELECT id, username, password, role FROM users WHERE email = '$email' ");
        $user = $querry->fetch();
        
        if($user && (password_verify($password, $user['password']) && ($tipe_user === $user['role']))){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] === 'owner')header('Location: ../view/admin/dashboard.php');
            else header('Location: ../view/user/beranda.php');
            exit();
        } else {
            echo "Login gagal ! email atau password salah";
        }
    }
?>