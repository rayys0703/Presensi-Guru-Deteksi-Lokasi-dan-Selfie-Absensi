<?php
session_start();

include_once 'main-admin.php';

$userid = '';

if (isset($_GET['nip'])) {
    $userid = $_GET['nip'];
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if ($op == 'hapus') {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT foto_absen FROM absen WHERE id_absen = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $fotoAbsen = $row["foto_absen"];
        if (!empty($fotoAbsen)) {
            $fotoPath = '../hasil_absen/' . $fotoAbsen;
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }
    }

    $stmt = $conn->prepare("DELETE FROM absen WHERE id_absen = :id");
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

$stmt = $conn->prepare("SELECT pengguna.nama, jabatan.jabatan_nama FROM pengguna INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id WHERE nip = :userid");
$stmt->bindParam(':userid', $userid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $nama_pengguna = $row["nama"];
    $nama_jabatan = $row["jabatan_nama"];
} else {
    $nama_pengguna = 0;
    $nama_jabatan = 0;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Tabel Absen</title>
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.7.1/dist/leaflet.css" />
</head>
<style>
    .mx-auto {
        max-width: 800px
    }

    .card {
        margin-top: 10px;
    }

    .table>tbody {
        font-size: 14px
    }

    td.text-center {
        vertical-align: middle;
    }

    .ftabsen,
    .swal2-image {
        border-radius: 8px
    }

    .ftabsen:hover {
        cursor: pointer;
        transform: scale(1.1);
        transition: all .3s ease
    }
</style>

<body>
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

    <div class="kolomkanan">
        <div class="mx-auto">

            <div class="card p-3" style="margin-bottom:50px">

                <?php
                date_default_timezone_set('Asia/Jakarta');

                $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
                $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

                $jumlah_hari = date('t', strtotime($tahun . '-' . $bulan . '-01'));

                // buat array kosong untuk absen
                $absen = array();

                $stmt = $conn->prepare("SELECT tanggal_absen, nip, keterangan FROM absen WHERE YEAR(tanggal_absen) = :tahun AND MONTH(tanggal_absen) = :bulan");
                $stmt->bindParam(':tahun', $tahun);
                $stmt->bindParam(':bulan', $bulan);
                $stmt->execute();

                $absen = array();
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tanggal = date('j', strtotime($row['tanggal_absen']));
                        $absen[$row['nip']][$tanggal] = $row['keterangan'];
                    }
                }
                ?>
                <h4 class="mb-3">Tabel Absen:
                    <?php echo $nama_pengguna ?> -
                    <?php echo $nama_jabatan ?>
                </h4>
                <form method="get" action="">
                    <input type="text" style="display:none" name="nip" value="<?php echo $userid; ?>">
                    <div class="form-group row mb-3">
                        <label for="tahun" class="col-sm-2 col-form-label">Tahun:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="tahun" name="tahun"
                                value="<?php echo $tahun; ?>">
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="bulan" class="col-sm-2 col-form-label">Bulan:</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="bulan" name="bulan">
                                <?php
                                $nama_bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                                for ($i = 1; $i <= 12; $i++) {
                                    $selected = ($i == $bulan) ? 'selected' : '';
                                    echo '<option value="' . sprintf("%02d", $i) . '" ' . $selected . '>' . $nama_bulan[$i - 1] . '</option>';
                                }
                                ?>
                            </select>

                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a class="btn btn-success"
                        href="ekspor_rekap?nip=<?php echo $userid; ?>&tahun=<?php echo $tahun; ?>&bulan=<?php echo $bulan; ?>">Ekspor
                        EXCEL</a>
                </form>
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="text-align:center">
                                <th width="150">Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                                <th width="180">Keterangan</th>
                                <th width="50">Foto</th>
                                <th>Lokasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
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

                            for ($i = 1; $i <= $jumlah_hari; $i++) {
                                // ambil nama hari dalam bahasa Indonesia
                                $nama_hari = $nama_hari_arr[date('N', strtotime($tahun . '-' . $bulan . '-' . $i))];

                                // ambil nama bulan dalam bahasa Indonesia
                                $nama_bulan = $nama_bulan_arr[intval($bulan) - 1];

                                $bg_color = ($nama_hari == 'Minggu') ? 'text-light bg-danger' : '';
                                echo '<tr class="' . $bg_color . '">';
                                echo '<td>' . $nama_hari . ', ' . str_pad($i, 2, '0', STR_PAD_LEFT) . ' ' . $nama_bulan . ' ' . $tahun . '</td>';

                                $query = "SELECT absen.id_absen, absen.nip, absen.id_status, status_absen.nama_status, absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, absen.keterangan, absen.foto_absen, absen.latlong 
          FROM absen 
          JOIN status_absen ON absen.id_status = status_absen.id_status 
          WHERE nip = :userid AND tanggal_absen = :tanggal
          ORDER BY absen.id_absen DESC";
                                $tanggal_absen = $tahun . '-' . $bulan . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $stmt = $conn->prepare($query);
                                $stmt->bindParam(':userid', $userid);
                                $stmt->bindParam(':tanggal', $tanggal_absen);
                                $stmt->execute();

                                if ($stmt->rowCount() > 0) {
                                    // jika data absen ditemukan
                                    $data_absen = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $id = $data_absen['id_absen'];
                                    $nip = $data_absen['nip'];
                                    $jam_masuk = $data_absen['jam_masuk'];
                                    $jam_keluar = $data_absen['jam_keluar'];
                                    $status = $data_absen['nama_status'];
                                    $keterangan = $data_absen['keterangan'];
                                    $fotoAbsen = $data_absen['foto_absen'];
                                    $latlong = $data_absen['latlong'];
                                    $tombolHapus = "<button type='button' onclick='return confirmDelete(`$nip`,`$id`)' class='btn btn-danger btn-sm'>Hapus</button>";
                                } else {
                                    // jika data absen tidak ditemukan
                                    $jam_masuk = '';
                                    $jam_keluar = '';
                                    $status = '';
                                    $keterangan = '-';
                                    $fotoAbsen = '';
                                    $latlong = '';
                                    $tombolHapus = '';
                                    // tambahkan keterangan untuk hari Minggu
                                    if ($nama_hari == 'Minggu') {
                                        $keterangan = 'Libur Akhir Pekan';
                                    } else {
                                        $keterangan = '';
                                    }
                                }

                                echo '<td>' . $jam_masuk . '</td>';
                                echo '<td>' . $jam_keluar . '</td>';
                                echo '<td>' . $status . '</td>';
                                echo '<td>' . $keterangan . '</td>';
                                if (!empty($fotoAbsen)) {
                                    echo '<td class="text-center">';
                                    echo '<img class="ftabsen" src="../hasil_absen/' . $fotoAbsen . '" alt="Foto Absen" id="' . $i . '" width="50px" height="50px" onclick="showFoto(\'' . $fotoAbsen . '\')">';
                                    echo '</td>';
                                } else {
                                    echo '<td></td>';
                                }
                                if (!empty($latlong)) {
                                    echo '<td class="text-center"><button type="button" class="tmlokasi btn btn-primary btn-sm" onclick="showModalMap(\'' . $latlong . '\')">Lihat</button></td>';
                                } else {
                                    echo '<td></td>';
                                }
                                echo '<td class="text-center">' . $tombolHapus . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                        <script>
                            function showFoto(fotoAbsen) {
                                swal.fire({
                                    title: 'Foto Absen: <?php echo $nama_pengguna ?>',
                                    imageUrl: '../hasil_absen/' + fotoAbsen,
                                    imageWidth: 300,
                                });
                            }
                            function confirmDelete(nip, id) {
                                Swal.fire({
                                    title: "Konfirmasi",
                                    text: "Apakah Anda yakin ingin menghapus absensi ini?",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonText: "Ya, Hapus",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "?nip=" + nip + "&op=hapus&id=" + id;
                                    }
                                });

                                return false;
                            }
                            var mymap;
                            $('#mapModal').on('hidden.bs.modal', function () {
                                mymap.remove();
                            });
                            function showModalMap(latlong) {
                                $('#mapModal').modal('show');
                                $('#mapModal').on('shown.bs.modal', function () {
                                    var mapContainer = document.getElementById('mapid');

                                    // Hapus map
                                    if (mapContainer && mapContainer._leaflet_id) {
                                        mapContainer._leaflet_id = null;
                                    }

                                    var coordinates = latlong.split(',');
                                    var latitude = parseFloat(coordinates[0]);
                                    var longitude = parseFloat(coordinates[1]);

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
                                    }).addTo(mymap).bindPopup("<?php echo $nama_pengguna; ?>").openPopup();
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
                            }
                        </script>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>