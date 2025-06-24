<?php 
    include_once('../../controller/pendaftaran_control.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="view-transition" content="same-origin">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem Kosan [Admin]</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../css/general.css">
  <link rel="stylesheet" href="../../css/header.css">
  <link rel="stylesheet" href="../../css/divs-manage.css">

  <style>
    .form-container {
    background-color: #ffffff;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .form-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #343a40;
    }

  .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }

  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="./dashboard.php">
        <i class="fas fa-home"></i> Kosan <strong>[Admin]</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-user me-1"></i>Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="manage-dpk.php"><i class="fa-solid fa-gears me-1"></i>Manage</a>
          </li>
        </ul>

        <div class="navbar-nav">
          <span class="navbar-text me-3">
            <i class="fas fa-user-circle me-1"></i>
            <a href="profile.php" class="text-decoration-none text-light"><?= $username ?></a>
          </span>
          <a class="nav-link" href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
  </nav>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <div class="navbar-collapse" id="navbar-manage">
        <ul class="navbar-manage me-auto">
          <li class="nav-man-item-manage">
            <a class="nav-link" href="manage-dpk.php"><i class="fas fa-user me-3"></i>Data Penyewa Kos</a>
          </li>
          <li class="nav-man-item-manage">
            <a class="nav-link" href="manage-dk.php"><i class="fa-solid fa-bed me-3"></i>Data Kamar</a>
          </li>
          <li class="nav-man-item-manage">
            <a class="nav-link active" href="manage-pd.php"><i class="fa-solid fa-clipboard me-3"></i>Pendaftaran</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Form: Pendaftaran Penyewa -->
  <div class="container mt-5">
    <div class="form-container">
      <div class="form-title">
        <i class="fa-solid fa-person-circle-plus me-2"></i>Pendaftaran Penyewa Kos
      </div>
      <form action="../../controller/pendaftaran_control.php" method="post" id="formPenyewa" novalidate>
        <div class="mb-3">
          <input class="form-control" type="text" name="name" placeholder="Nama Penyewa" required>
        </div>
        <div class="mb-3">
          <label class="form-label text-white">Kamar</label>
          <select class="form-select" name="room" id="room-select">
            <option value="room">Kamar</option>
            <!-- pilihan room -->
          </select>
        </div>
        <div class="mb-3">
          <input class="form-control" type="text" name="username" placeholder="Username" required>
        </div>
        <div class="mb-3">
          <input class="form-control" type="tel" name="phone" placeholder="Phone Number" pattern="[0-9]{10,13}" required>
        </div>
        <div class="mb-3">
          <input class="form-control" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="mb-3">
          <input class="form-control" type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
        <button type="reset" class="btn btn-secondary" form="formPenyewa">Reset</button>
      </form>
    </div>
  </div>

  <!-- Form: Penambahan Kamar -->
  <div class="container mt-5 mb-5">
    <div class="form-container">
      <div class="form-title">
        <i class="fa-solid fa-person-shelter me-2"></i>Penambahan Kamar
      </div>
      <form action="../../controller/tambah_kamar_control.php" method="post" id="formKamar" novalidate>
        <div class="mb-3">
          <input class="form-control" type="text" name="no_kamar" placeholder="Nomor Kamar" required>
        </div>
        <div class="mb-3">
          <input class="form-control" type="number" name="harga" placeholder="Harga Kamar" required min="1">
        </div>
        <button type="submit" name="submit" class="btn btn-primary me-2">Submit</button>
        <button type="reset" class="btn btn-secondary" form="formKamar">Reset</button>
      </form>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script>

    let room_list = [];

    function loadRoomList(){
      const savedData = <?= json_encode($room_list) ?>;
      if(savedData) room_list = savedData;
    }

    function loadRoomsOption(){
      
      const roomOption = document.getElementById('room-select');
      room_list.forEach(room => {        
        const option = document.createElement('option');
        option.value = room.id + "|" + room.price;
        option.textContent = room.room_number;
        roomOption.appendChild(option);
      });
    }

    // Simple form validation
    document.querySelectorAll("form").forEach(form => {
      form.addEventListener("submit", function (e) {
        if (!form.checkValidity()) {
          e.preventDefault();
          e.stopPropagation();
          form.classList.add('was-validated');
        }
      });
    });

    function logout() {
            localStorage.clear();
            alert('Anda telah logout.');
            window.location.href = '../../login.php'; 
    }

    document.addEventListener('DOMContentLoaded', function() {
      loadRoomList();
      loadRoomsOption();
    });

  </script>
</body>
</html>
