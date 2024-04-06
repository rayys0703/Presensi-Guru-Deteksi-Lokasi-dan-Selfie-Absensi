<?php
// Rayya

require_once('database.php');
$database = new Database();
$conn = $database->__construct();

if (!isset($_SESSION['nip'])) {
	header("Location: login");
    exit();
} else {
header("Cache-Control: no-cache, must-revalidate");

$userid = $_SESSION['nip'];

$current_page = basename($_SERVER['PHP_SELF']);

/* Eksekusi query dan mengambil isi umum
$sqlUtama = "SELECT id, nip, nama, jabatan_id, guru FROM pengguna WHERE nip = '$userid'";
$result = $conn->query($sqlUtama);
$hasil = mysqli_query($conn, $sqlUtama);

// Khusus join tabel jabatan
$sql3 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, jabatan.jabatan_nama, pengguna.guru, pengguna.foto_profil
         FROM pengguna
         INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id
		     WHERE nip = '$userid'";
$joinges = mysqli_query($conn, $sql3);
$hasiljoin = mysqli_fetch_array($joinges);
$jabatan = $hasiljoin['jabatan_nama'];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $nama = $row['nama'];
        $nip = $row['nip'];
        $jabatan = $hasiljoin['jabatan_nama'];
        $guru = $row['guru'];
    }
}*/

$sqlUtama = "SELECT id, nip, nama, jabatan_id, penempatan_id FROM pengguna WHERE nip = ?";
$stmt = $conn->prepare($sqlUtama);
$stmt->bindParam(1, $userid);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// join tabel jabatan
$sql3 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, jabatan.jabatan_nama, penempatan.penempatan_nama, pengguna.foto_profil
         FROM pengguna
         INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id
         INNER JOIN penempatan ON pengguna.penempatan_id = penempatan.penempatan_id
         WHERE nip = ?";
$stmt2 = $conn->prepare($sql3);
$stmt2->bindParam(1, $userid);
$stmt2->execute();
$joinges = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$hasiljoin = $joinges[0];

if (count($result) > 0) {
    foreach ($result as $row) {
        $nama = $row['nama'];
        $nip = $row['nip'];
        $jabatan = $hasiljoin['jabatan_nama'];
        $penempatan = $hasiljoin['penempatan_nama'];
    }
}

if ($hasiljoin['foto_profil'] == NULL) {
  $nama_file = "default.png";
} else {
  $nama_file = $hasiljoin['foto_profil'];
  $path_to_file = "foto_profil/".$nama_file;
  if (!file_exists($path_to_file)) {
    $nama_file = "default.png";
  }
}

require_once('absenclass.php');
$obj = new Absensiswa;
$d = $obj->data_Absen($userid);

// Mendapatkan hari saat ini B.Indonesia
$hari = date("l");
$hari_ini = "";

switch ($hari) {
    case "Monday":
        $hari_ini = "Senin";
        break;
    case "Tuesday":
        $hari_ini = "Selasa";
        break;
    case "Wednesday":
        $hari_ini = "Rabu";
        break;
    case "Thursday":
        $hari_ini = "Kamis";
        break;
    case "Friday":
        $hari_ini = "Jumat";
        break;
    case "Saturday":
        $hari_ini = "Sabtu";
        break;
    case "Sunday":
        $hari_ini = "Minggu";
        break;
}

$nama_bulan_arr = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
$tanggal = date('Y-m-d');
$nama_bulan = $nama_bulan_arr[intval(date('m', strtotime($tanggal))) - 1];

echo'
<!DOCTYPE html>
<html lang="id-ID" xml:lang="id-ID">
<head>

  <!--Viewport -->
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type"/>
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible"/>

  <!-- Jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!--CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="header/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
</head>

<body>';
if (isset($_SESSION['nip'])) {
echo'
<!-- App Sidebar -->

<div class="kolomkanan">
<header class="l-header">
<nav class="iniheader navbar p-3 navbar-dark justify-content-start" style="">
<button class="btn" style="border:0;background:none" type="button" data-toggle="collapse" data-target="#offcanvas" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="toggle">
    <i class="bi bi-list open"></i>
    <i class="bi bi-x close"></i>
</div>
<a href="login.php" style="margin-left:8px">Presensi Guru berbasis Web</a>
</nav>
</header>
</div>

<nav class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" data-bs-keyboard="false" data-bs-backdrop="true" data-bs-scroll="true">
<div class="leftP top" id="Left-profile">
<div class="profileIcon leftC flex solo">
<label class="a flexIns fc" for="forProfile">
<span class="av">
<span class="avatar flex center bgAlt">
';?>
    <img class="inifoto" src="foto_profil/<?php echo $nama_file;?>" alt="<?php echo $nama_file;?>">
    <?php
echo'
</span>
</span>
<span class="n flex column">
<span class="fontS">
<span class="name" style="font-size:17px">';
    echo "" . $nama;
    echo'</span>
    </span>
    <span class="opacity" style="font-size:14px">';
    echo "NIP " . $nip;
    echo'</span>';
echo'
</span>
</label>
</div>
</div>

<div class="offcanvas-body px-0">

  <ul class="list-unstyled ps-0">
    
    <ul class="leftNav flex column noList" itemscope="itemscope" itemtype="https://schema.org/SiteNavigationElement">
<li class="leftC mb-1 br2">
<a aria-label="Beranda" class="a flex op i20 home" href="login.php" itemprop="url">
<svg class="line" viewBox="0 0 24 24"><path d="M12 18V15"></path><path d="M10.07 2.81997L3.14002 8.36997C2.36002 8.98997 1.86002 10.3 2.03002 11.28L3.36002 19.24C3.60002 20.66 4.96002 21.81 6.40002 21.81H17.6C19.03 21.81 20.4 20.65 20.64 19.24L21.97 11.28C22.13 10.3 21.63 8.98997 20.86 8.36997L13.93 2.82997C12.86 1.96997 11.13 1.96997 10.07 2.81997Z"></path></svg>
<div class="n text">
<span>
Beranda
</span>
</div>
</a>
</li>
<li class="leftC dr br">
<details open>
<summary class="a flex noWrap op i20">
<svg class="line" viewBox="0 0 24 24"><path d="M22 11V17C22 21 21 22 17 22H7C3 22 2 21 2 17V7C2 3 3 2 7 2H8.5C10 2 10.33 2.44 10.9 3.2L12.4 5.2C12.78 5.7 13 6 14 6H17C21 6 22 7 22 11Z"></path><path d="M8 2H17C19 2 20 3 20 5V6.38"></path></svg>
<span class="flexIn center grow text">
<span>Lainnya</span>
<svg class="line r" viewBox="0 0 24 24"><path d="M19.9201 8.94995L13.4001 15.47C12.6301 16.24 11.3701 16.24 10.6001 15.47L4.08008 8.94995"></path></svg>
</span>
</summary>
<ul class="n">
<li itemprop="name">
<a href="absensi" itemprop="url">
<span>Absensi</span>
</a>
</li>
<li itemprop="name">
<a href="profil" itemprop="url">
<span>Profil</span>
</a>
</li>
<li class="m" data-text="Mini heading" itemprop="name">
<a href="riwayat" itemprop="url">
<span>Riwayat</span>
</a>
</ul>
</details>
</li>
<li class="leftC br">
<a aria-label="Logout" class="a flex op i20" href="logout.php" itemprop="url">
<svg class="line" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><g transform="translate(2.000000, 2.000000)"><line x1="19.791" y1="10.1207" x2="7.75" y2="10.1207"></line><polyline points="16.8643 7.2047 19.7923 10.1207 16.8643 13.0367"></polyline><path d="M0.2588,5.6299 C0.5888,2.0499 1.9288,0.7499 7.2588,0.7499 C14.3598,0.7499 14.3598,3.0599 14.3598,9.9999 C14.3598,16.9399 14.3598,19.2499 7.2588,19.2499 C1.9288,19.2499 0.5888,17.9499 0.2588,14.3699" transform="translate(7.309300, 9.999900) scale(-1, 1) translate(-7.309300, -9.999900) "></path></g></svg>
<div class="n text">
<span itemprop="name">Logout</span>
</div>
</a>
</li>
</ul>
</ul>
</div>
</nav>
    <!-- * App Sidebar -->
    
    <div class="kolomkiri">
    <div class="navigation active">
    <ul>
        <li class="list';if ($current_page == "beranda.php") {echo " active";} echo'">
            <b></b>
            <b></b>
            <a href="beranda" data-text="Home">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="title">Home</span>
            </a>
        </li>
        <li class="list';if ($current_page == "absensi.php") {echo " active";} echo'">
            <b></b>
            <b></b>
            <a href="absensi" data-text="Absen">
                <span class="icon"><i class="bi bi-calendar-plus"></i></span>
                <span class="title">Absen</span>
            </a>
        </li>
        <li class="list';if ($current_page == "profil.php") {echo " active";} echo'">
            <b></b>
            <b></b>
            <a href="profil" data-text="Profil">
                <span class="icon"><i class="bi bi-person-lines-fill"></i></span>
                <span class="title">Profil</span>
            </a>
        </li>
        <li class="list';if ($current_page == "riwayat.php") {echo " active";} echo'">
            <b></b>
            <b></b>
            <a href="riwayat" data-text="Riwayat">
                <span class="icon"><i class="bi bi-card-checklist"></i></span>
                <span class="title">Riwayat</span>
            </a>
        </li>
        <li class="list">
            <b></b>
            <b></b>
            <a href="logout" data-text="Logout">
                <span class="icon"><i class="bi bi-door-open"></i></span>
                <span class="title">Logout</span>
            </a>
        </li>
    </ul>
</div>
</div>

<!-- * Nav MOBILE -->
<div class="navmob">
        <div class="effect"></div>
        <a href="beranda" class="';if ($current_page == "beranda.php") {echo " active";} echo'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16"><path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/></svg>
            <span class="text">Beranda</span>
        </a>
        <a href="absensi" class="';if ($current_page == "absensi.php") {echo " active";} echo'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/></svg>
            <span class="text">Absen</span>
        </a>
        <a class="plus" id="sidebarCollapse" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button" aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
        </a>
        <a href="riwayat" class="';if ($current_page == "riwayat.php") {echo " active";} echo'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/></svg>
            <span class="text">Riwayat</span>
        </a>
        <a href="profil" class="';if ($current_page == "profil.php") {echo " active";} echo'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
            <span class="text">Profil</span>
        </a>
    </div>

    ';
  }
 }?>
<script>
let menuToggle = document.querySelector('.toggle');
let navigation = document.querySelector('.navigation');

menuToggle.onclick = function(){menuToggle.classList.toggle('active');navigation.classList.toggle('active')}

let list = document.querySelectorAll('.list');
for (let i=0; i<list.length; i++){
    list[i].onclick = function(){
        let j = 0;
        while(j < list.length){
            list[j++].className = 'list';
        }
        list[i].className = 'list active';
    }
}

function hello(){
  navigation.classList.toggle('active')
}
setTimeout(hello,1000);
</script>