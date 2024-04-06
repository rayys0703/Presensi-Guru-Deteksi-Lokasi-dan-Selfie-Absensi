<?php
// Rayya
session_start();
include_once 'cfgall.php';

if (isset($_POST['simpan'])) {
    $tanggal_absen = date('Y-m-d');
    $jam_masuk = date('H:i:s');
    $id_status = $_POST['id_status'];
    $keterangan = $_POST['keterangan'];

    $nama_file = $_FILES['lampiran']['name'];
    $image_ext = explode('.', $nama_file);
    $file_ext = strtolower(end($image_ext));
    $ukuran_file = $_FILES['lampiran']['size'];
    $tipe_file = $_FILES['lampiran']['type'];
    $tmp_file = $_FILES['lampiran']['tmp_name'];

    $stmt = $conn->prepare("SELECT * FROM absen WHERE nip=? AND tanggal_absen=?");
    $stmt->bindParam(1, $userid);
    $stmt->bindParam(2, $tanggal_absen);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        // Jika sudah absen
        echo '<script>
    popupJudul = "Gagal!";
    popupText = "Kamu sudah absen hari ini!";
    popupIcon = "error";
    </script>';
    } else {
        if (!empty($nama_file)) {
            $allowed_types = array('image/jpeg', 'image/jpg', 'image/png');
            if (!in_array($tipe_file, $allowed_types)) {
                echo '<script>
            swal.fire({
                title: "Gagal!",
                text: "Jenis file yang diunggah tidak diizinkan. Hanya file JPEG, JPG, dan PNG yang diperbolehkan.",
                icon: "error",
            }).then((result) => {
                window.location.href = "login";
            })
            </script>';
            } elseif ($ukuran_file > 500000) {
                echo '<script>
            swal.fire({
                title: "Gagal!",
                text: "Ukuran file terlalu besar. Maksimal ukuran file yang diizinkan adalah 500KB.",
                icon: "error",
            }).then((result) => {
                window.location.href = "login";
            })
            </script>';
            } else {
                $nama_file = $userid . "_" . date('Y-m-d') . "." . $file_ext;
                $tujuan_file = "hasil_absen/" . $nama_file;
                move_uploaded_file($tmp_file, $tujuan_file);
            }
        } else {
            $nama_file = NULL;
        }

        $stmt = $conn->prepare("INSERT INTO absen (nip, tanggal_absen, jam_masuk, id_status, tgl_keluar, keterangan, foto_absen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $userid);
        $stmt->bindParam(2, $tanggal_absen);
        $stmt->bindParam(3, $jam_masuk);
        $stmt->bindParam(4, $id_status);
        $stmt->bindParam(5, $tanggal_absen);
        $stmt->bindParam(6, $keterangan);
        $stmt->bindParam(7, $nama_file);
        $stmt->execute();

        echo '<script>
        popupJudul = "Berhasil!";
        popupText = "Kamu telah melakukan izin kehadiran.";
        popupIcon = "success";
        </script>';

        $stmt->closeCursor();

    }

    echo '<script>
    swal.fire({
        title: "" + popupJudul,
        text: "" + popupText,
        icon: "" + popupIcon,
    }).then((result) => {
        setTimeout(function () {
            window.location.href = "login";
         }, 400);
    })
    </script>';
}

$stmt = $conn->prepare("SELECT jarak, latitude, longitude, fitur_foto FROM pengaturan");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    $jarakIdeal = $result[0]['jarak'];
    $latSekolah = $result[0]['latitude'];
    $longSekolah = $result[0]['longitude'];
    $fitur_foto = $result[0]['fitur_foto'];
    $isFitur_foto = $fitur_foto == 1 ? 'checked' : '';
} else {
    $latSekolah = 0;
    $longSekolah = 0;
}

$stmt = $conn->prepare("SELECT id_jadwal, nama_hari, waktu_masuk, waktu_pulang FROM jadwal WHERE nama_hari = ?");
$stmt->bindParam(1, $hari_ini);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    $id_jadwal = $result[0]["id_jadwal"];
    $hari_ini = $result[0]["nama_hari"];
    $jam_masuk = $result[0]["waktu_masuk"];
    $jam_pulang = $result[0]["waktu_pulang"];
} else {
    $jam_masuk = date('H:i:s');
    $jam_pulang = date('H:i:s');
}

$stmt->closeCursor();

$jam_masuk = date('H:i', strtotime($jam_masuk)); // mengubah format waktu
$jam_masuk = $jam_masuk . " WIB"; // menambahkan "WIB" pada akhir string

$jam_pulang = date('H:i', strtotime($jam_pulang)); // mengubah format waktu
$jam_pulang = $jam_pulang . " WIB"; // menambahkan "WIB" pada akhir string

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil | SMP SMA MKGR Kertasemaya</title>
    <style>
        .mx-auto {
            max-width: 800px
        }

        .card {
            margin-top: 10px;
        }

        .fade {
            background-color: rgb(0 0 0 / 60%);
        }

        .button-text {
            line-height: 1.3em
        }

        .button-alt {
            display: flex;
            justify-content: center;
            align-items: center
        }

        .button-alt svg {
            width: 13px;
            height: 13px;
            margin-right: 5px
        }

        #video {
            width: 280px !important;
        }

        .menu-beranda {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media only screen and (max-width: 576px) {
            .fs-small {
                font-size: .9rem !important;
            }

            .btn-sm {
                padding: .15rem .5rem !important;
                font-size: .85rem !important;
                border-radius: 10px !important;
            }

            .text-center {
                text-align: left !important;
            }

            #video {
                width: 180px !important;
            }
        }

        @media only screen and (max-width: 400px) {
            #video {
                width: 120px !important;
            }
        }
    </style>
</head>

<body>
    <div class="kolomkanan">
        <div class="mx-auto">

            <div class="d-none card mb-5 p-3">
                <div class="leftP">
                    <div class="profileIcon leftC flex solo">
                        <label class="a flexIns fc" for="forProfile">
                            <span class="avatar flex center">
                                <img class="iniprofil" src="foto_profil/<?php echo $nama_file; ?>"
                                    alt="<?php echo $nama_file; ?>">
                            </span>
                            <span class="n flex column">
                                <span class="fontS">
                                    <h4>
                                        <?php echo $nama ?>
                                    </h4>
                                </span>
                                <p class="opacity" style="margin-bottom:0">
                                    NIP
                                    <?php echo $nip ?> -
                                    <?php echo $jabatan ?> -
                                    <?php echo $penempatan ?>
                                </p>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="card p-3 mb-5 text-center">
                <!--<div class="alert alert-info" role="alert">
                    Gunakan akses lokasi agar dapat berjalan.
                </div>-->
                <div class="mb-3" style="align-items:center;margin-right:auto;margin-left:auto">
                    <video id="video" height="auto" style="transform: scaleX(-1);border-radius: 18px;" autoplay></video>
                </div>

                <div class="mb-3 fs-small">
                    <span class="d-block"><i class="bi-geo-alt text-success"></i> <a class="text-success"
                            href="https://goo.gl/maps/zwucsvHDvmTCVtYUA" target="_blank">Lokasi Sekolah</a>: <b
                            id="my-location">belum terdeteksi</b></span>
                </div>
                <div class="mb-3 fs-small">
                    <span class="d-block">
                        Lokasi Anda: <b id="your-location">belum terdeteksi</b> - <button type="button"
                            class="btn btn-primary btn-sm" onclick="showModalMap()">Lihat</button></span>
                    <span class="d-none">Lat: <b id="your-latitude">belum terdeteksi</b> - | - Long: <b
                            id="your-longitude">belum terdeteksi</b></span>
                </div>
                <div class="mb-3 fs-small">
                    <div class="d-block">
                        <span>Jarak Anda dari Sekolah: <b id="our-distance">belum terdeteksi</b> <b>(Maksimal:
                                <?php echo $jarakIdeal ?> km)
                            </b></span>
                    </div>
                </div>
                <div class="mb-3 fs-small">
                    <div class="d-block">
                        <span>Saat ini: <b id="jam">belum terdeteksi</b> <b>WIB -
                                <?php echo $hari_ini . ', ' . date('d', strtotime($tanggal)) . ' ' . $nama_bulan . ' ' . date('Y', strtotime($tanggal)) ?>
                            </b></span>
                    </div>
                </div>
                <div class="mb-3 fs-small">
                    <div class="d-block">
                        <span id="blocation" class="d-flex justify-content-center align-items-center"
                            style="color:#0d6efd;font-weight:700"><a id="izinkan-lokasi" type="button">Izinkan
                                Lokasi</a> <i class="bi bi-box-arrow-up-right"
                                style="margin-left:8px;font-size:13px"></i></span>
                    </div>
                </div>
                <!--< button id = "izinkan-lokasi" type = "button" class="btn btn-primary" >
                        Izinkan akses lokasi saya
            </button > -->
                <div class="menu-beranda" style="flex-wrap:wrap;justify-content:space-between">
                    <div class="item">
                        <label id="captureButton">
                            <div class="button-container btn-outline-biru">
                                <div class="button-icon">
                                    <i class="bi bi-calendar2-check"></i>
                                </div>
                                <div class="button-text">
                                    <div class="button-title">ABSEN MASUK</div>
                                    <div class="button-alt"><svg class='line' xmlns='http://www.w3.org/2000/svg'
                                            viewBox='0 0 24 24'>
                                            <path
                                                d='M184.7647,181.67261l-2.6583-2.65825a2,2,0,0,1-.5858-1.41423v-3.75937'
                                                transform='translate(-169.5206 -166.42857)'></path>
                                            <rect class='cls-3' x='2' y='2' width='20' height='20' rx='5'></rect>
                                        </svg>
                                        <?php echo $jam_masuk; ?>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div class="item">
                        <a href="absen_keluar.php">
                            <div class="button-container btn-outline-biru">
                                <div class="button-icon">
                                    <i class="bi bi-calendar2-minus"></i>
                                </div>
                                <div class="button-text">
                                    <div class="button-title">ABSEN KELUAR</div>
                                    <div class="button-alt"><svg class='line' xmlns='http://www.w3.org/2000/svg'
                                            viewBox='0 0 24 24'>
                                            <path
                                                d='M184.7647,181.67261l-2.6583-2.65825a2,2,0,0,1-.5858-1.41423v-3.75937'
                                                transform='translate(-169.5206 -166.42857)'></path>
                                            <rect class='cls-3' x='2' y='2' width='20' height='20' rx='5'></rect>
                                        </svg>
                                        <?php echo $jam_pulang; ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="item">
                        <label onclick="showModal()">
                            <div class="button-container btn-outline-danger">
                                <div class="button-icon">
                                    <i class="bi bi-calendar2-x"></i>
                                </div>
                                <div class="button-text">
                                    <div class="button-title">IZIN KEHADIRAN</div>
                                    <div class="button-alt">Cuti tidak hadir</div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!--
                            <div class="item">
                                <div class="sa">
                                    <a href="absen_masuk.php">
                                        <div class="icon-wrapper bg-biru">
                                            <i class="bi bi-calendar2-check"></i>
                                        </div>
                                        <strong>Absen Masuk</strong>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="sa">
                                    <a href="absen_keluar.php">
                                        <div class="icon-wrapper bg-biru">
                                            <i class="bi bi-calendar2-minus"></i>
                                        </div>
                                        <strong>Absen Keluar</strong>
                                    </a>
                                </div>
                            </div>
                            <div class="item">
                                <div class="sa">
                                    <label onclick="showModal()">
                                        <div class="icon-wrapper bg-biru">
                                            <i class="bi bi-calendar2-x"></i>
                                        </div>
                                        <strong>Izin Kehadiran</strong>
                                    </label>
                                </div>
                            </div> -->
                </div>
            </div>
            <!-- < div class="p-3">
                <a class="btn btn-outline-dark btn-sm" href="absen_masuk.php">Absen masuk</a>
                <a class="btn btn-outline-dark btn-sm" href="absen_keluar.php">Absen keluar</a>
                <a class="btn btn-outline-success btn-sm" href="absen_sakit.php">Absen sakit</a>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="showModal()">Izin Tidak
                    Hadir</button>
        </div>
        -->
        </div>

        <!--Modal Izin-->
        <div class="modal fade" id="absenModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Izin Kehadiran</h5>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="id_status">Jenis Absen</label>
                                <?php
                                $stmt = $conn->prepare("SELECT id_status, nama_status FROM status_absen WHERE id_status <> ?");
                                $excludedId = 1;
                                $stmt->bindParam(1, $excludedId);
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($result) > 0) {
                                    echo '<select class="form-control" id="absenSelect" name="id_status">';
                                    foreach ($result as $row) {
                                        $id_status = $row["id_status"];
                                        $nama_status = $row["nama_status"];
                                        echo '<option value="' . $id_status . '">' . $nama_status . '</option>';
                                    }
                                    echo '</select>';
                                } else {
                                    echo "Tidak ada data yang ditemukan.";
                                }

                                $stmt->closeCursor();
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="keteranganTextarea">Keterangan (opsional):</label>
                                <textarea class="form-control" name="keterangan" id="keteranganTextarea"
                                    rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="lampiranInput">Lampiran Foto Bukti/Surat:</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="lampiranInput" name="lampiran">
                                    <label class="custom-file-label" for="lampiranInput">Pilih file</label>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onClick="$('#absenModal').modal('hide')">Batal</button>
                        <!--<button type="button" class="btn btn-primary"
                                    onclick="insertAbsensi(<?php echo $userid; ?>)">Submit</button>-->
                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary" />
                    </div>
                    </form>
                </div>
            </div>
        </div>


        <!--Modal Map-->
        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mapModalLabel">Peta Lokasi</h5>
                    </div>
                    <div class="modal-body">
                        <div id="mapid" style="height: 400px;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            onClick="$('#mapModal').modal('hide')">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />

    <script>
        function showModal() {
            $('#absenModal').modal('show');
        }
        function showModalMap() {
            $('#mapModal').modal('show');
        }

        function insertAbsensi(userid) {
            var absenSelect = document.getElementById("absenSelect");
            var id_status = absenSelect.options[absenSelect.selectedIndex].value;

            if (id_status === "") {
                alert("Anda harus memilih jenis absen!");
            } else {
                var keterangan = document.getElementById("keteranganTextarea").value;
                console.log(userid);

                // Mengirim data absen menggunakan AJAX
                $.ajax({
                    url: "",
                    type: "POST",
                    data: {
                        userid: userid,
                        id_status: id_status,
                        keterangan: keterangan
                    },
                    success: function (data) {
                        $('#absenModal').modal('hide');
                    },
                    error: function () {
                        alert("Terjadi kesalahan saat menyimpan absen!");
                    }
                });
            }
        }

        //////////////////////////////////////////////////////////////

        window.addEventListener("load", () => {
            const allowLocationButton = document.querySelector(
                "#izinkan-lokasi"
            );
            const buttonLocation = document.querySelector("#blocation");
            const myLocation = document.querySelector("#my-location");
            const ourDistance = document.querySelector("#our-distance");
            const yourLatitude = document.querySelector("#your-latitude");
            const yourLongitude = document.querySelector("#your-longitude");
            const yourLocation = document.querySelector("#your-location");

            const latSekolah = <?php echo $latSekolah ?>; // Latitude SMP SMA MKGR Kertasemaya
            const longSekolah = <?php echo $longSekolah ?>; // Longitude SMP SMA MKGR Kertasemaya
            myLocation.innerText = "Kertasemaya, Indramayu.";
            jarak = 1000; // Jarak Default

            allowLocationButton.addEventListener('click', function () {
                requestLocationPermission();
            });

            function requestLocationPermission() {
                <?php if (!isset($_POST['simpan'])) { ?>
                    // Meminta izin akses lokasi
                    navigator.permissions.query({ name: 'geolocation' }).then(function (result) {
                        if (result.state == 'granted') {
                            // Jika izin akses lokasi telah diberikan, panggil fungsi untuk mendapatkan lokasi
                            isSupportLocation();
                        } else if (result.state == 'prompt') {
                            // Jika pengguna belum memberikan izin akses lokasi, minta izin akses lokasi
                            navigator.geolocation.getCurrentPosition(isSupportLocation, showError);
                        } else if (result.state == 'denied') {
                            // Jika pengguna telah memblokir izin akses lokasi, tampilkan alert
                            swal.fire({
                                title: "Gagal Mendeteksi!",
                                html: "Anda telah memblokir akses lokasi. Harap izinkan akses lokasi pada pengaturan browser Anda.",
                                icon: "error",
                            });
                            buttonLocation.classList.remove("d-none");
                            tombolAbsenMasuk.setAttribute('style', 'pointer-events:none;opacity:.65');
                            tombolAbsenKeluar.setAttribute('style', 'pointer-events:none;opacity:.65');
                        }
                        result.onchange = function () {
                            // Jika pengguna mengubah izin akses lokasi, panggil fungsi untuk memeriksa ulang izin akses lokasi
                            requestLocationPermission();
                        }
                    });
                <?php } ?>
            }

            function isSupportLocation() {
                if (navigator.geolocation) {
                    buttonLocation.classList.remove("d-none");
                    navigator.geolocation.getCurrentPosition(showPosition);
                } else {
                    swal({
                        title: "Gagal",
                        text: "browser ini tidak mendukung akses lokasi.",
                        icon: "error",
                    });
                }
            }

            function showPosition(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;
                latlong = latitude + ", " + longitude;
                yourLatitude.innerText = latitude;
                yourLongitude.innerText = longitude;

                var mymap;
                $('#mapModal').on('hidden.bs.modal', function () {
                    mymap.remove();
                });
                $('#mapModal').on('shown.bs.modal', function () {
                    mymap = L.map('mapid').setView([latitude, longitude], 13);

                    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                        attribution: 'Map data &copy; <a href="https://www.mapbox.com/">Mapbox</a>',
                        maxZoom: 18,
                        tileSize: 512,
                        zoomOffset: -1,
                        id: 'mapbox/streets-v11',
                        accessToken: 'pk.eyJ1IjoiYWRpZ3VuYXdhbnhkIiwiYSI6ImNrcWp2Yjg2cDA0ZjAydnJ1YjN0aDNnbm4ifQ.htvHCgSgN0UuV8hhZBfBfQ'
                    }).addTo(mymap);

                    L.marker([latitude, longitude]).addTo(mymap);
                    L.circle([latitude, longitude], 550, {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5
                    }).addTo(mymap).bindPopup("<?php echo $nama; ?>").openPopup();
                    var popup = L.popup();
                    function onMapClick(e) {
                        popup
                            .setLatLng(e.latlng)
                            .setContent("" + e.latlng.toString())
                            .openOn(mymap);
                    }
                    mymap.on('click', onMapClick);

                    //var marker = L.marker([latitude, longitude]).addTo(mymap);
                });

                buttonLocation.classList.add("d-none");
                tombolAbsenMasuk.setAttribute('style', '');
                tombolAbsenKeluar.setAttribute('style', '');

                const apiUrl = `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`;

                fetch(apiUrl, { headers: { "Content-Type": "application/json" } })
                    .then((res) => res.json())
                    .then((res) => {
                        console.log(res);
                        const city = res.city === "" ? "" : res.city + ", ";
                        const provinsi = res.principalSubdivision === "" ? "" : res.principalSubdivision + ", ";
                        const negara =
                            res.countryName === "" ? "" : " " + res.countryName;

                        yourLocation.innerText = `${city}${provinsi}${negara}`;

                        const userLat = latitude;
                        const userLon = longitude;

                        calculateDistance(userLat, userLon);
                    });
            }

            function calculateDistance(userLat, userLon) {
                const R = 6371e3; // metres
                const φ1 = (userLat * Math.PI) / 180; // φ, λ in radians
                const φ2 = (latSekolah * Math.PI) / 180;
                const Δφ = ((latSekolah - userLat) * Math.PI) / 180;
                const Δλ = ((longSekolah - userLon) * Math.PI) / 180;

                const a =
                    Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                    Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                const d = R * c; // in metres
                const distance = d.toFixed(0);

                const distanceInKm = distance / 1000;

                jarak = Intl.NumberFormat('id-ID', { minimumFractionDigits: 3 }).format(distanceInKm);

                console.log(distanceInKm.toFixed(3));

                console.log(Intl.NumberFormat().format(distanceInKm) + " kilometer");

                ourDistance.innerText = jarak + " km";
            }

            // Tombol Absen Masuk
            var tombolAbsenMasuk = document.getElementById("captureButton");
            /*if (tombolAbsenMasuk) {
                tombolAbsenMasuk.addEventListener('click', function (event) {
                    Webcam.snap(function (data_uri) {
                        event.preventDefault();
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'absen_masuk.php';

                        var inputlatlong = document.createElement('input');
                        inputlatlong.type = 'hidden';
                        inputlatlong.name = 'latlong';
                        inputlatlong.value = latlong;
                        form.appendChild(inputlatlong);

                        var inputJarak = document.createElement('input');
                        inputJarak.type = 'hidden';
                        inputJarak.name = 'jarak';
                        inputJarak.value = jarak;
                        form.appendChild(inputJarak);

                        var inputDataUri = document.createElement('input');
                        inputDataUri.type = 'hidden';
                        inputDataUri.name = 'data_uri';
                        inputDataUri.value = data_uri;
                        form.appendChild(inputDataUri);

                        document.body.appendChild(form);
                        form.submit();
                    });
                });
            }*/

            // Tombol Absen Keluar
            var tombolAbsenKeluar = document.querySelector('a[href="absen_keluar.php"]');
            if (tombolAbsenKeluar) {
                tombolAbsenKeluar.addEventListener('click', function (event) {
                    event.preventDefault();
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'absen_keluar.php';

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'jarak';
                    input.value = jarak;

                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                });
            }

            requestLocationPermission();

        });

        // Mengambil elemen video dan tombol ambil foto
        const video = document.getElementById("video");
        const captureButton = document.getElementById("captureButton");

        <?php if ($fitur_foto == 0) { ?>
                video.style.display = "none";
        <?php } ?>

            function requestCameraPermission() {
            <?php if (!isset($_POST['simpan']) && $fitur_foto == 1) { ?>
                        // Meminta izin akses kamera
                        navigator.permissions.query({ name: 'camera' }).then(function (result) {
                            if (result.state === 'granted') {
                                // Jika izin akses kamera telah diberikan, coba akses kamera lagi
                                accessCamera();
                            } else if (result.state === 'prompt') {
                                // Jika pengguna belum memberikan izin akses kamera, minta izin akses kamera
                                navigator.mediaDevices.getUserMedia({ video: true })
                                    .then(function (stream) {
                                        video.srcObject = stream;
                                    })
                                    .catch(function (error) {
                                        showError("Error accessing webcam: " + error);
                                    });
                            } else if (result.state === 'denied') {
                                // Jika pengguna telah memblokir izin akses kamera, tampilkan pesan kesalahan
                                Swal.fire({
                                    title: "Gagal Mendeteksi!",
                                    html: "Anda telah memblokir akses kamera. Harap izinkan kamera pada pengaturan browser Anda.",
                                    icon: "error",
                                });
                                // Tambahkan logika tambahan jika diperlukan setelah pemblokiran izin akses kamera
                            }
                            result.onchange = function () {
                                // Jika pengguna mengubah izin akses kamera, panggil fungsi untuk memeriksa ulang izin akses kamera
                                requestCameraPermission();
                            };
                        });
            <?php } ?>
        }

        // Fungsi untuk mengakses kamera dan mengatur aliran video
        function accessCamera() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function (stream) {
                    video.srcObject = stream;
                })
                .catch(function (error) {
                    showError("Error accessing webcam: " + error);
                });
        }

        // Fungsi untuk menampilkan pesan kesalahan
        function showError(error) {
            console.log(error);
        }

        // Panggil fungsi untuk meminta izin akses kamera
        requestCameraPermission();

        ///////////////////////////////////////////////////////

        // Mengambil foto saat tombol ambil foto diklik
        captureButton.addEventListener("click", async function () {
            <?php if ($fitur_foto == 1) { ?>
                            // Memeriksa izin kamera
                            try {
                        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        // Izin kamera diberikan, melanjutkan dengan kode pengambilan foto
                        stream.getTracks().forEach((track) => track.stop()); // Menutup stream kamera yang tidak digunakan
                        takePhoto();
                    } catch (error) {
                        // Izin kamera tidak diberikan atau terjadi kesalahan lain
                        swal.fire({
                            title: "Gagal!",
                            html: "Harap izinkan akses kamera pada pengaturan browser Anda.",
                            icon: "error",
                        });
                    }
            <?php } else { ?>
                Swal.fire({
                    title: "Ingin Absen Masuk Sekarang?",
                    text: "",
                    showCancelButton: true,
                    confirmButtonText: "Masuk",
                    cancelButtonText: "Batal",
                }).then((result) => {
                    // Jika tombol "Absen Masuk" diklik
                    if (result.isConfirmed) {
                        const form = document.createElement("form");
                        form.action = "absen_masuk.php";
                        form.method = "POST";
                        form.style.display = "none";

                        const jarakInput = document.createElement("input");
                        jarakInput.type = "hidden";
                        jarakInput.name = "jarak";
                        jarakInput.value = jarak;
                        form.appendChild(jarakInput);

                        const inputlatlong = document.createElement('input');
                        inputlatlong.type = 'hidden';
                        inputlatlong.name = 'latlong';
                        inputlatlong.value = latlong;
                        form.appendChild(inputlatlong);

                        document.body.appendChild(form);

                        form.submit();
                    }
                });
            <?php } ?>
        });

        // Fungsi untuk mengambil foto
        function takePhoto() {
            // Membuat elemen canvas untuk mengambil foto
            const canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");

            // Mengatur ukuran canvas sesuai dengan ukuran video
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Menggambar video pada canvas
            context.scale(-1, 1); // Tambahkan baris ini untuk membalikkan gambar secara horizontal
            context.drawImage(video, 0, 0, -canvas.width, canvas.height);

            // Mengubah foto menjadi URL data (base64)
            const photo = canvas.toDataURL("image/jpeg", 0.5); // Mengompresi gambar dengan kualitas 50%

            if (photo) {
                Swal.fire({
                    title: "Ingin Absen Masuk Sekarang?",
                    text: "",
                    imageUrl: photo,
                    imageAlt: "Foto Absen",
                    showCancelButton: true,
                    confirmButtonText: "Masuk",
                    cancelButtonText: "Batal",
                    preConfirm: () => {
                        return new Promise((resolve) => {
                            const image = new Image();
                            image.src = photo;
                            image.onerror = () => {
                                Swal.showValidationMessage("Foto tidak dapat dimuat!");
                                resolve(false);
                            };
                            image.onload = () => {
                                resolve(true);
                            };
                        });
                    },
                }).then((result) => {
                    // Jika tombol "Absen Masuk" diklik
                    if (result.isConfirmed) {
                        const form = document.createElement("form");
                        form.action = "absen_masuk.php";
                        form.method = "POST";
                        form.style.display = "none";

                        const jarakInput = document.createElement("input");
                        jarakInput.type = "hidden";
                        jarakInput.name = "jarak";
                        jarakInput.value = jarak;
                        form.appendChild(jarakInput);

                        const inputlatlong = document.createElement('input');
                        inputlatlong.type = 'hidden';
                        inputlatlong.name = 'latlong';
                        inputlatlong.value = latlong;
                        form.appendChild(inputlatlong);

                        const photoInput = document.createElement("input");
                        photoInput.type = "hidden";
                        photoInput.name = "photo";
                        photoInput.value = photo;
                        form.appendChild(photoInput);

                        document.body.appendChild(form);

                        form.submit();
                    }
                });
            } else {
                Swal.fire("Tidak dapat menangkap gambar!");
            }
        }

        // Jam saat ini
        var myVar = setInterval(myTimer, 1000);

        function myTimer() {
            var d = new Date();
            d.setHours(d.getHours()); // Waktu Indonesia Barat (GMT+7)
            var t = d.toLocaleTimeString('en-US', { hour12: false });
            document.getElementById("jam").innerHTML = t;
        } myTimer();
    </script>
</body>

</html>