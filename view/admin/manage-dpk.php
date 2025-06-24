<?php 
  include_once('../../controller/data_penghuni_control.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="view-transition" content="same-origin">
    <link rel="stylesheet" href="../../css/general.css">
    <title>Sistem Kosan [Admin]</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/divs-manage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="./dashboard.php"><i class="fas fa-home"></i> Kosan <strong>[Admin]</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-user me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage-dpk.php">
                           <i class="fa-sharp-duotone fa-solid fa-gears me-1"></i>Manage
                        </a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-circle me-1"></i>
                        <a href="profile.php" class="text-decoration-none"><?= $username ?></a>
                    </span>
                    <a class="nav-link" href="#" onclick="logout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="navbar-collapse" id="navbar-manage">
                <ul class="navbar-manage me-auto">
                    <li class="nav-man-item-manage">
                        <a class="nav-link active" href="manage-dpk.php">
                            <i class="fas fa-user me-3"></i>Data Penyewa Kos
                        </a>
                    </li>

                    <li class="nav-man-item-manage">
                        <a class="nav-link" href="manage-dk.php">
                           <i class="fa-solid fa-bed me-3"></i>Data Kamar
                        </a>
                    </li>
                    
                    <li class="nav-man-item-manage">
                        <a class="nav-link" href="manage-pd.php">
                           <i class="fa-solid fa-clipboard me-3"></i>Pendaftaran
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<table class="table table-striped mt-5 mx-auto" style="width: 80%" id="tenants">
  <thead class="text-center">
    <tr class="table-dark">
      <th scope="col">Nama</th>
      <th scope="col">Kamar</th>
      <th scope="col">No telepon</th>
      <th scope="col">Jatuh tempo</th>
      <th scope="col">Tanggal bayar</th>
      <th scope="col">Status bayar</th>
      <th scope="col">Tanggal bayar berikutnya</th>
      <th scope="col"></th>
    </tr>
  </thead>

  <tbody class="text-center" id="tbody">
    <!-- table content -->
  </tbody>
</table>

  <script>
    let tenants_data = [];

    function getRestDayPayment(due_date) {
      const dueDate = new Date(due_date);
      const today = new Date();

      let restDay = (dueDate - today) / (1000 * 60 * 60 * 24);
      restDay = Math.floor(restDay);
      return (restDay + 1);
    }

    function formatDisplayDate(dateString) {
            if (!dateString) return '-';
            const [year, month, day] = dateString.split('-');
            const dateObj = new Date(year, month - 1, day);
            return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function loadData() {
      const savedData = <?= json_encode($tenants_data) ?>;
      if (savedData) tenants_data = savedData;
    }

    function loadDataTable(params) {
        const body = document.getElementById('tbody');
        body.innerHTML = '';
        if (tenants_data.length === 0){
          body.innerHTML = `
            <tr>
              <td> - </td>
              <td> - </td>
              <td> - </td>
              <td> - </td>
              <td> - </td>
              <td> - </td>
            </tr>
            `;
          return;
        }

        tenants_data.forEach(penghuni => {
          let restDayPayment = getRestDayPayment(penghuni.due_date);
          console.log(restDayPayment);
          
          body.innerHTML += `
            <tr class= "${penghuni.status === 'paid' ? 'table-success' : restDayPayment < 0 ? 'table-danger' : ''}">
              <td>${penghuni.name}</td>
              <td>${penghuni.room_number}</td>
              <td>${penghuni.phone_number}</td>
              <td>${formatDisplayDate(penghuni.due_date)}</td>
              <td>${formatDisplayDate(penghuni.pay_date) ?? '-'}</td>
              <td>${penghuni.status === 'paid' ? 'sudah bayar' : restDayPayment >= 0 ? 'belum bayar' : 'belum bayar (nunggak ' + Math.abs(restDayPayment) + ' hari)'}</td>
              <td>${formatDisplayDate(penghuni.next_pay_date)}</td>
              <td>
                ${penghuni.status === 'unpaid' ? `<a href="../../controller/update_payment_control.php?tenant_id=${penghuni.tenant_id}" class="text-primary text-decoration-none">update status pembayaran</a>` : ''}
              </td>
            </tr>
          `;

        });
    }

    function logout() {
            localStorage.clear();
            alert('Anda telah logout.');
            window.location.href = '../login.php'; 
      }

    document.addEventListener('DOMContentLoaded', function() {
      loadData();
      loadDataTable();
    });
  </script>
  </body>
</html>