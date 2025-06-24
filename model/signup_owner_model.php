<?php 
    include_once(__DIR__ . '/../config/database.php');

    function insert_new_owner($data, $conn){
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data_value = array_values($data);

        $querry = $conn->prepare("INSERT INTO users (name, username, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?, 'owner')");
        $querry->execute($data_value);
    }

    function get_new_owner_id($username, $conn){
        $querry = $conn->query("SELECT id FROM users WHERE username = '$username'");
        $result = $querry->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }
    
    function insert_new_kost($data, $owner_username, $conn){
        $owner_id = get_new_owner_id($owner_username, $conn);
        $data_value = array_values($data);

        $querry = $conn->prepare("INSERT INTO kosts(kost_name, address, owner_id) VALUES (?, ?, $owner_id)");
        $querry->execute($data_value);
    }
?>