<?php

session_start();
include_once 'main-admin.php';
if(isset($_POST['logout'])){
  session_destroy();
  header("Location: index");
}

// Query untuk menghitung jumlah pengguna
$sql = "SELECT COUNT(*) AS total_pengguna FROM pengguna";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
  $jumlah_pengguna = $result["total_pengguna"];
} else {
  $jumlah_pengguna = 0;
}

$sql1 = "SELECT COUNT(*) AS total_absen FROM absen WHERE DATE(tanggal_absen) = :today";
$stmt1 = $conn->prepare($sql1);
$stmt1->bindParam(':today', $today);
$stmt1->execute();
$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

if ($result1) {
  $total_absen = $result1["total_absen"];
} else {
  $total_absen = 0;
}

$sql2 = "SELECT COUNT(*) AS total_jabatan FROM jabatan";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($result2) {
  $jumlah_jabatan = $result2["total_jabatan"];
} else {
  $jumlah_jabatan = 0;
}

$sql3 = "SELECT COUNT(*) AS total_status FROM status_absen";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute();
$result3 = $stmt3->fetch(PDO::FETCH_ASSOC);

if ($result3) {
  $jumlah_status = $result3["total_status"];
} else {
  $jumlah_status = 0;
}

// set default timezone
date_default_timezone_set('Asia/Jakarta');

// ambil tahun dan bulan dari parameter GET, atau gunakan tanggal hari ini
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

// ambil jumlah hari pada bulan ini
$jumlah_hari = date('t', strtotime($tahun . '-' . $bulan . '-01'));


// ambil jumlah hari pada bulan dan tahun yang dipilih
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
$nama_hari_arr = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
$nama_bulan_arr = array(
  'Januari',
  'Februari',
  'Maret',
  'April',
  'Mei',
  'Juni',
  'Juli',
  'Agustus',
  'September',
  'Oktober',
  'November',
  'Desember'
);
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$nama_hari = $nama_hari_arr[date('N', strtotime($tanggal))];
$nama_bulan = $nama_bulan_arr[intval(date('m', strtotime($tanggal))) - 1];

/////////////

$query = "SELECT absen.id_absen, absen.nip, pengguna.nama, pengguna.foto_profil, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.foto_absen, absen.latlong 
          FROM absen 
          JOIN status_absen ON absen.id_status = status_absen.id_status
          JOIN pengguna ON absen.nip = pengguna.nip
          WHERE tanggal_absen = :tanggal
          ORDER BY absen.id_absen DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':tanggal', $tanggal);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id-ID" xml:lang="id-ID">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  
  <style>
    .mx-auto {
      max-width: 800px
    }

    .card {
      margin-top: 10px;
      border-radius: 30px;
    }

    .card-container {
      display: flex;
    }
  </style>
</head>

<body>
  <div class="mx-auto">

    <div class="cardBox mb-5">
      <a href="pengguna">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $jumlah_pengguna; ?>
            </div>
            <div class="cardName">Pengguna</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-people"></i>
          </div>
        </div>
      </a>

      <a href="rekap_harian">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $total_absen; ?>
            </div>
            <div class="cardName">Absen Hari Ini</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-activity"></i>
          </div>
        </div>
      </a>

      <a href="jabatan">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $jumlah_jabatan; ?>
            </div>
            <div class="cardName">Jabatan</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-person-badge"></i>
          </div>
        </div>
      </a>

      <a href="status">
        <div class="card">
          <div>
            <div class="numbers">
              <?php echo $jumlah_status; ?>
            </div>
            <div class="cardName">Status Absen</div>
          </div>

          <div class="iconBx">
            <i class="bi bi-list-check"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="terakhirAbsen mb-5">
      <div class="cardHeader">
        <h4>Absensi Hari Ini - <i>Realtime</i></h4>
      </div>

      <table id="riwayat_absensi"></table>

      <script>
        function loadAbsensi() {
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              var data = xhr.responseText;
              document.getElementById("riwayat_absensi").innerHTML = data;
            }
          };
          xhr.open("GET", "muat_absensi_realtime.php", true);
          xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function () {
          setInterval(loadAbsensi, 5000); // Memuat data absensi setiap 5 detik (5000 ms)
          loadAbsensi();
        });
      </script>
    </div>

  </div>
</body>

</html>