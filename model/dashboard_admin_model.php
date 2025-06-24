<?php 
    include_once(__DIR__.'/../config/database.php');

    function get_kost_info($id, $conn){
        $querry = $conn->query("SELECT COUNT(rooms.id) AS jumlah_kamar, COUNT(tenants.room_id) AS kamar_terisi FROM rooms
                                LEFT JOIN tenants ON rooms.id = tenants.room_id
                                INNER JOIN kosts ON rooms.kost_id = kosts.id
                                WHERE kosts.owner_id = $id");

        return $querry->fetch(PDO::FETCH_ASSOC);
    }

    function get_kost_identity($id, $conn){
        $querry = $conn->query("SELECT id FROM kosts WHERE owner_id = $id");
        $result = $querry->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    function get_revenue($id, $conn, $month_year){
        $kost_id = get_kost_identity($id, $conn);
        $querry = $conn->query("SELECT SUM(amount) AS revenue FROM bills
                                INNER JOIN tenants ON bills.tenant_id = tenants.id
                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                WHERE status = 'paid' AND DATE_FORMAT(due_date, '%Y-%m') = '$month_year' AND rooms.kost_id = $kost_id");

        $result = $querry->fetch(PDO::FETCH_ASSOC);

        return $result ?? 0;
    }

    function get_kost_revenues($id, $conn){
        $this_month = date('Y-m');
        $month_ago = date('Y-m', strtotime($this_month . '-01 -1 month'));

        $revenue_this_month = get_revenue($id, $conn, $this_month);
        $revenue_month_ago = get_revenue($id, $conn, $month_ago);

        return ['this_month' => $revenue_this_month['revenue'], 
                'month_ago' => $revenue_month_ago['revenue']];
    }

    function get_recent_activity($id, $conn, $month_year){
        $kost_id = get_kost_identity($id, $conn);
        $querry = $conn->query("SELECT users.name AS name, bills.pay_date, bills.amount
                                FROM bills
                                INNER JOIN tenants ON bills.tenant_id = tenants.id
                                INNER JOIN users ON tenants.userID = users.id
                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                WHERE rooms.kost_id = $kost_id AND status = 'paid' AND DATE_FORMAT(bills.pay_date, '%Y-%m') = '$month_year'");

        return $querry->fetchAll(PDO::FETCH_ASSOC);
    }
?>