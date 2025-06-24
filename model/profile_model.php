<?php 
    include_once(__DIR__ .'/../config/database.php');
    
    function get_user_profile(string $id, PDO $conn) {
        $user_profile = $conn->query("   SELECT users.name, users.username, rooms.room_number, users.phone_number, users.email, users.password,  tenants.start_date
                                                FROM tenants
                                                INNER JOIN users ON tenants.userID = users.id
                                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                                WHERE userID = $id;");

        return $user_profile->fetch(PDO::FETCH_ASSOC);
    }
?>