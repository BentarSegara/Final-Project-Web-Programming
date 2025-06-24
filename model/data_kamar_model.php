<?php 
    include_once(__DIR__ .'/../config/database.php');


    function get_kost_identity($id, $conn){
        $querry = $conn->query("SELECT id FROM kosts WHERE owner_id = $id");
        $result = $querry->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    function get_rooms_data($id, $conn){
        $kost_id = get_kost_identity($id, $conn);
        $querry = $conn->query("SELECT rooms.room_number, users.name AS tenant, rooms.price
                                FROM rooms
                                LEFT JOIN tenants ON rooms.id = tenants.room_id
                                LEFT JOIN users ON tenants.userID = users.id
                                WHERE rooms.kost_id = $kost_id");

        $rooms = $querry->fetchAll(PDO::FETCH_ASSOC);
        for ($i=0; $i < sizeof($rooms); $i++) $rooms[$i]['tenant'] = $rooms[$i]['tenant'] ?? 'Kosong';

        return $rooms;
    }

?>