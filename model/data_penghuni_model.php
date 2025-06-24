<?php 

    include_once(__DIR__ .'/../config/database.php');

    function get_kost_identity($id, $conn){
        $querry = $conn->query("SELECT id FROM kosts WHERE owner_id = $id");
        $result = $querry->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
    }

    function get_bill($tenant_id, $conn) {
        $querry = $conn->query("SELECT amount, due_date FROM bills WHERE tenant_id = $tenant_id");
        
        return $querry->fetch(PDO::FETCH_ASSOC);
    }

    function get_tenants_data($id , $conn){
        $kost_id = get_kost_identity($id, $conn);
        $this_month = date('Y-m');
        $querry = $conn->query("SELECT tenants.id AS tenant_id, users.name, users.phone_number, rooms.room_number, bills.status, bills.due_date, bills.pay_date,
                                CASE
                                    WHEN bills.status = 'paid' THEN DATE_ADD(due_date, INTERVAL 1 MONTH)
                                    ELSE due_date
                                END as next_pay_date
                                FROM tenants
                                INNER JOIN users ON tenants.userID = users.id
                                INNER JOIN rooms ON tenants.room_id = rooms.id
                                LEFT JOIN bills ON tenants.id = bills.tenant_id
                                WHERE rooms.kost_id = $kost_id AND DATE_FORMAT(due_date, '%Y-%m') = '$this_month'");

        return $querry->fetchAll(PDO::FETCH_ASSOC);
    }

    function update_tenant_payment_status($tenant_id, $conn){
        $now  = date('Y-m-d');
        $this_month = date('m');
        $querry = "UPDATE bills SET status = 'paid', pay_date = '$now'
                    WHERE tenant_id = $tenant_id AND MONTH(due_date) = '$this_month' ";
        $conn->exec($querry);
    }

    function insert_new_bill($tenant_id, $conn){
        $bills = get_bill($tenant_id, $conn);
        $next_due_date = (new DateTime($bills['due_date']))->modify('+3 month');
        $month = $next_due_date->format('F');
        $year = $next_due_date->format('Y');
        $next_due_date = $next_due_date->format('Y-m-d');


        var_dump($next_due_date);


        $querry = $conn->prepare("INSERT INTO bills (tenant_id, month, amount, status, due_date, year) VALUES (?, ?, ?, ?, ?, ?)");
        $querry->execute([$tenant_id, $month, $bills['amount'], 'unpaid', $next_due_date, $year]);

    }

    function filltered_data($id, $conn){
        $tenants_data = get_tenants_data($id, $conn);
        for ($index=0; $index < sizeof($tenants_data); $index++) { 
            $next_date_bill = date('Y-m-d', strtotime($tenants_data[$index]['due_date'] . '+1 month'));
            if($tenants_data[$index]['status'] === 'paid'){
                $tenants_data[$index]['next_pay_date'] = $next_date_bill;
            }
            else{
                $tenants_data[$index]['next_pay_date'] = $tenants_data[$index]['due_date'];
            }
        }
        return $tenants_data;
    }
?>
