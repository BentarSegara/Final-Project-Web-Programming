<?php 
    include_once(__DIR__ .'/../config/database.php');

    function get_kost_identity($id, $conn){
        $querry = $conn->query("SELECT id FROM kosts WHERE owner_id = $id");
        $result = $querry->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    function get_rooms($id, $conn){
        $kost_id = get_kost_identity($id, $conn);
        $querry = $conn->query("SELECT rooms.id, rooms.room_number, rooms.price 
                                FROM rooms 
                                LEFT JOIN tenants ON rooms.id = tenants.room_id
                                WHERE rooms.kost_id = $kost_id AND tenants.id IS NULL");

        return $querry->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_user_id($conn, $username){
        $querry = $conn->query("SELECT id FROM users WHERE username = '$username'");
        $id = $querry->fetch(PDO::FETCH_ASSOC);

        return $id['id'];
    }

    function get_tenant_id($conn, $room_id){
        $querry = $conn->query("SELECT id FROM tenants WHERE room_id = '$room_id'");
        $id = $querry->fetch(PDO::FETCH_ASSOC);
        return $id['id'];
    }
    
    function create_new_user($conn, $data){
        $querry = $conn->prepare("INSERT INTO users (name, username, phone_number, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");

        $querry->execute([$data['name'], $data['username'], $data['phone'], $data['email'], $data['password'], 'tenant']);
    }

    function create_first_bill($conn, $tenant_id, $amount){
        $now = explode("-", date('Y-m'));
        $querry = $conn->prepare("INSERT INTO bills (tenant_id, month, year, amount, due_date) VALUES (?, ?, ?, ?, ?)");
        $querry->execute([$tenant_id, $now[1], $now[0], $amount, date('Y-m-d')]);

    }

    function insert_new_tenant($conn, $data){
        $now = date('Y-m-d');
        $user_data = array_intersect_key($data, array_flip(['name', 'username', 'phone', 'email', 'password']));
        $room_data = explode("|", $data['room']);

        create_new_user($conn, $user_data);
        $user_id = get_user_id($conn, $user_data['username']);

        $querry = $conn->prepare("INSERT INTO tenants (room_id, userID, start_date) VALUES (?, ?, ?)");
        $querry->execute([$room_data[0], $user_id, $now]);
        $tenant_id = get_tenant_id($conn, $room_data[0]);

        create_first_bill($conn, $tenant_id, $room_data[1]);
    }

    function insert_new_room($id, $conn, $data){
        $kost_id = get_kost_identity($id, $conn);
        $querry = $conn->prepare("INSERT INTO rooms (kost_id, room_number, price) VALUES (?, ?, ?)");
        $querry->execute([$kost_id, $data['no_kamar'], $data['harga']]);
    }
?>