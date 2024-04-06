<?php

include_once '../cfgdb.php';

if (!isset($_SESSION['username'])) {
    header("Location: index");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
$today = date("Y-m-d");

echo '
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
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  <!--CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css?key=2">
</head>
<style>
.btn{border-radius:18px !important;}
.card, .modal-content {border:0;border-radius: 30px !important;}
.card-header {background:none !important}
</style>
<body>

<!-- Konten -->

<div class="mx-auto">
<header class="l-header">
<nav class="iniheader navbar p-3 navbar-dark justify-content-start" style="">
<button class="btn" style="border:0;background:none" type="button" data-toggle="collapse" data-target="#offcanvas" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="toggle">
    <i class="bi bi-list open"></i>
    <i class="bi bi-x close"></i>
</div>
<a href="beranda" style="margin-left:8px;color:black;text-decoration:none">Presensi Guru berbasis Web</a>
</nav>
</header>
</div>
    
    <div class="kolomkiri">
    <div class="navigation active">
    <ul>
        <li class="list';
if ($current_page == "beranda.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="beranda" data-text="Beranda">
                <span class="icon"><i class="bi bi-house-door"></i></span>
                <span class="title">Beranda</span>
            </a>
        </li>
        <li class="list';
if ($current_page == "pengguna.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="pengguna" data-text="Data Pengguna">
                <span class="icon"><i class="bi bi-people"></i></span>
                <span class="title">Pengguna</span>
            </a>
        </li>
        <li class="list';
if ($current_page == "jabatan.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="jabatan" data-text="Jabatan">
                <span class="icon"><i class="bi bi-person-badge"></i></span>
                <span class="title">Jabatan</span>
            </a>
        </li>
        <li class="list';
if ($current_page == "jadwal.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="jadwal" data-text="Jadwal">
                <span class="icon"><i class="bi bi-calendar-week"></i></span>
                <span class="title">Jadwal</span>
            </a>
        </li>
        <li class="list';
if ($current_page == "status.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="status" data-text="Status Absen">
                <span class="icon"><i class="bi bi-list-check"></i></span>
                <span class="title">Status</span>
            </a>
        </li>
        <li class="list';
if ($current_page == "rekap.php" || $current_page == "hasil_rekap.php" || $current_page == "rekap_harian.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="rekap" data-text="Rekap">
                <span class="icon"><i class="bi bi-journals"></i></span>
                <span class="title">Rekap</span>
            </a>
        </li>
        <li class="list';
if ($current_page == "pengaturan.php") {
    echo " active";
}
echo '">
            <b></b>
            <b></b>
            <a href="pengaturan" data-text="Pengaturan">
                <span class="icon"><i class="bi bi-gear-fill"></i></span>
                <span class="title">Pengaturan</span>
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
        <a href="beranda" class="';
if ($current_page == "beranda.php") {
    echo " active";
}
echo '">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16"><path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/></svg>
            <span class="text">Beranda</span>
        </a>
        <a href="absensi" class="';
if ($current_page == "absensi.php") {
    echo " active";
}
echo '">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/></svg>
            <span class="text">Absen</span>
        </a>
        <a class="plus" id="sidebarCollapse" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button" aria-label="Toggle menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/></svg>
        </a>
        <a href="riwayat" class="';
if ($current_page == "riwayat.php") {
    echo " active";
}
echo '">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/></svg>
            <span class="text">Riwayat</span>
        </a>
        <a href="profil" class="';
if ($current_page == "profil.php") {
    echo " active";
}
echo '">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"/></svg>
            <span class="text">Profil</span>
        </a>
    </div>

    ';
?>
<script>
    let menuToggle = document.querySelector('.toggle');
    let navigation = document.querySelector('.navigation');

    menuToggle.onclick = function () { menuToggle.classList.toggle('active'); navigation.classList.toggle('active') }

    let list = document.querySelectorAll('.list');
    for (let i = 0; i < list.length; i++) {
        list[i].onclick = function () {
            let j = 0;
            while (j < list.length) {
                list[j++].className = 'list';
            }
            list[i].className = 'list active';
        }
    }

    /*function hello() {
        navigation.classList.toggle('active')
    }
    setTimeout(hello,1000);*/
</script>