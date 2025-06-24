<?php 
    include_once(__DIR__ .'/../config/database.php');
    
    function get_admin_profile(string $id, PDO $conn) {
        $user_profile = $conn->query("   SELECT users.name, users.username, users.phone_number, users.email, users.password
                                                FROM users
                                                WHERE id = $id;");

        return $user_profile->fetch(PDO::FETCH_ASSOC);
    }
?>
