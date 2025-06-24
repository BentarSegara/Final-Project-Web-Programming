<?php 
  include_once('../../controller/dashboard_control.php');
  $this_month = new DateTime();
  $month_ago = new DateTime('-1 month');

  function formatRupiah($nominal){
    $format_idr_currency = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);

    return $format_idr_currency->formatCurrency($nominal, 'IDR');
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="view-transition" content="same-origin">
    <link rel="stylesheet" href="../../css/general.css">
    <title>Sistem Kosan [Admin]</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/divs-dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href=""><i class="fas fa-home"></i> Kosan <strong>[Admin]</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-user me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage-dpk.php">
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
  <div>
      <div class="nama-kosan">
        <span>Kos <?= $username ?></span>
      </div>
      <div class="boxes">
        <div class="stats-indikasi">
          <div class="statistik"><i class="fa-solid fa-chart-simple me-3"></i>Statistik Kos</div>
            <div class="counter-kamar align-items-end">
              <div class="total-kamar">
                <span>Total Kamar: <?= $rooms['jumlah_kamar'] ?></span>
              </div>
              <div class="kamar-terisi"> 
                <span>Total kamar-terisi: <?= $rooms['kamar_terisi'] ?></span>
              </div>
            </div>
          <div class="pendapatan"><i class="fa-solid fa-money-bill me-3"></i>Pendapatan
            <div class="bulan-nominal ms-5">
              <span class="h3">
                <?= $this_month->format('M Y') ?> : <strong><?= formatRupiah($revenues['this_month']) ?></strong>
              </span>
            </div>
          </div>
          <div class="pendapatan"><i class="fa-solid fa-money-bill me-3"></i>Pendapatan Bulan Lalu
            <div class="bulan-nominal ms-5">
              <span class="h3">
                <?= $month_ago->format('M Y') ?> : <strong><?= formatRupiah($revenues['month_ago']) ?></strong>
              </span>
            </div>
          </div>
        </div>

        <div class="history">
          <div class="title">
            <i class="fa-solid fa-clock-rotate-left me-3"></i>Aktivitas Terkini
          </div>
          <div class="inner-sbox" id="activity-container">
            <!-- recent activity content -->
          </div>
        </div>
      </div>
  </div>

  <script>
    let activities = [];

    function formatDisplayDate(dateString) {
      if (!dateString) return '-';
        const [year, month, day] = dateString.split('-');
        const dateObj = new Date(year, month - 1, day);
        return dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
      }

      function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
      }

    function loadData() {
      const savedData = <?= json_encode($activities) ?>;
      if (savedData) {
        activities = savedData;
      }
    }
    
    function loadRecentActivity() {
      console.log(activities);
      
      const recentActivityDiv = document.getElementById('activity-container');
      recentActivityDiv.innerHTML = '';

      if (activities.length === 0){
        recentActivityDiv.innerHTML = '<p class="text-center text-muted">Belum ada aktivitas terbaru.</p>';
        return;
      }

      activities.forEach(activity => {
        const activityDiv = document.createElement('div');
        activityDiv.className = 'activity';
  
        activityDiv.innerHTML = `
              <div class="title-activity mb-3">
                  <span>
                    <i class="fa-solid fa-cash-register me-3"></i> Pembayaran Uang Kosan
                  </span>
              </div>
              <div class="waktu-terjadi mb-3">
                  <span class="h5">${formatDisplayDate(activity.pay_date)}</span>
              </div>
              <div class="isi-activity mb-3">
                <span class="h5">${activity.name} sudah membayar ${formatCurrency(activity.amount)}</span>
              </div>
        `;
        recentActivityDiv.appendChild(activityDiv);
      });
    }

    function logout() {
            localStorage.clear();
            alert('Anda telah logout.');
            window.location.href = '../login.php'; 
    }

    document.addEventListener('DOMContentLoaded', function() {
      loadData();
      loadRecentActivity(); 
    });


  </script>
</body>
</html>
