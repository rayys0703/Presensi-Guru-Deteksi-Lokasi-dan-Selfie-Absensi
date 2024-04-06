<?php
include_once '../cfgdb.php';

date_default_timezone_set('Asia/Jakarta');

$tanggal = date('Y-m-d');

$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
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
$nama_hari = $nama_hari_arr[date('N', strtotime($tanggal))];
$nama_bulan = $nama_bulan_arr[intval(date('m', strtotime($tanggal))) - 1];

$query = "SELECT absen.id_absen, absen.nip, pengguna.nama, pengguna.foto_profil, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.foto_absen, absen.latlong 
      FROM absen 
      JOIN status_absen ON absen.id_status = status_absen.id_status
      JOIN pengguna ON absen.nip = pengguna.nip
      WHERE tanggal_absen = :tanggal
      ORDER BY absen.jam_masuk DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':tanggal', $tanggal);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$saat_ini = time();

if (count($result) > 0) {
  foreach ($result as $data_absen) {
    $nip = $data_absen['nip'];
    $nama = $data_absen['nama'];
    $jam_masuk = $data_absen['jam_masuk'];
    $jam_keluar = $data_absen['jam_keluar'];
    $status = $data_absen['nama_status'];
    $keterangan = $data_absen['keterangan'];
    $fotoAbsen = $data_absen['foto_absen'];
    $latlong = $data_absen['latlong'];

    $absensi_time = strtotime($data_absen['jam_masuk']);

    // Menghitung selisih waktu antara waktu absensi dan waktu saat ini
    $time_diff = $saat_ini - $absensi_time;

    // Konversi selisih waktu menjadi menit
    $minutes_diff = round($time_diff / 60);

    if ($data_absen['foto_profil'] == NULL) {
      $nama_file = "default.png";
    } else {
      $nama_file = $data_absen['foto_profil'];
      $path_to_file = "../foto_profil/" . $nama_file;
      if (!file_exists($path_to_file)) {
        $nama_file = "default.png";
      }
    }

    echo '<tr>
            <td width="60px">
              <div class="imgBx"><img src="../foto_profil/' . $nama_file . '" alt=""></div>
            </td>
            <td>
              <h4>' . $nama . ' <br> <span>Telah melakukan absen <b>' . $status . '</b> ' . formatWaktu($minutes_diff) . '</b></span></h4>
            </td>
          </tr>';
  }
} else {
  ?>
  <!-- Tampilkan pesan jika tidak ada absensi -->
  <div class="alert alert-info" style="margin-top:15px">Tidak/belum ada absensi untuk
    <?php echo $nama_hari . ', ' . date('d', strtotime($tanggal)) . ' ' . $nama_bulan . ' ' . date('Y', strtotime($tanggal)) ?>
  </div>
  <?php
}

// Mngatur format waktu
function formatWaktu($minutes_diff)
{
  if ($minutes_diff < 1) {
    return "baru saja";
  } elseif ($minutes_diff === 60) {
    return "1 jam yang lalu";
  } elseif ($minutes_diff > 60) {
    $hours = floor($minutes_diff / 60);
    $remaining_minutes = $minutes_diff % 60;
    return $hours . " jam " . $remaining_minutes . " menit yang lalu";
  } else {
    return $minutes_diff . " menit yang lalu";
  }
}

?>