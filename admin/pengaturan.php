<?php
session_start();

include_once 'main-admin.php';

$id = "";
$jarak = "";
$latitude = "";
$longitude = "";
$sukses = "";
$error = "";

// untuk simpan pengaturan
if (isset($_POST['simpan'])) {
    $batas_telat = $_POST['batas_telat'];
    $jarak = $_POST['jarak'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $fitur_foto = isset($_POST['fitur_foto']) ? 1 : 0;

    if ($batas_telat && $jarak && $latitude && $longitude) {
        $sql = "UPDATE pengaturan SET batas_telat = :batas_telat, jarak = :jarak, latitude = :latitude, longitude = :longitude, fitur_foto = :fitur_foto WHERE id_pengaturan = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':batas_telat', $batas_telat);
        $stmt->bindParam(':jarak', $jarak);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':fitur_foto', $fitur_foto);
        $stmt->execute();

        $sukses = "Data berhasil diupdate";
    } else {
        $error = "Silakan masukkan semua data";
    }
}

// untuk ganti password
if (isset($_POST['submit'])) {
    $id_admin = 1;
    $oldpassword = md5($_POST['oldpassword']);
    $newpassword = md5($_POST['newpassword']);
    $confirmpassword = md5($_POST['confirmpassword']);

    $query = "SELECT password FROM admin WHERE id_admin=?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id_admin]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        $row = $result[0];
        $oldpassword_db = $row['password'];

        if ($oldpassword == $oldpassword_db) {
            if ($newpassword == $confirmpassword) {
                $query = "UPDATE admin SET password=? WHERE id_admin=?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$newpassword, $id_admin]);
                $script = "<script>
            Swal.fire(
                'Berhasil!',
                'Password berhasil diubah.',
                'success'
            );</script>";
                echo $script;
            } else {
                $script = "<script>
            Swal.fire(
                'Gagal!',
                'Password baru tidak cocok dengan konfirmasi password.',
                'error'
            );</script>";
                echo $script;
            }
        } else {
            $script = "<script>
            Swal.fire(
                'Gagal!',
                'Password lama salah.',
                'error'
            );</script>";
            echo $script;
        }
    } else {
        $script = "<script>
            Swal.fire(
                'Gagal!',
                'error'
            );</script>";
        echo $script;
    }
}

$sql = "SELECT * FROM pengaturan";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$batas_telat = $result['batas_telat'];
$jarak = $result['jarak'];
$latitude = $result['latitude'];
$longitude = $result['longitude'];
$fitur_foto = $result['fitur_foto'];
$isFitur_foto = $fitur_foto == 1 ? 'checked' : '';

if ($error) {
    ?>
    <script>
        Swal.fire({
            title: "<?php echo $error ?>",
            icon: "error",
        })
    </script>
    <?php
}
if ($sukses) {
    ?>
    <script>
        Swal.fire({
            title: "<?php echo $sukses ?>",
            icon: "success",
        })
    </script>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukkan data -->
        <div class="card mb-3">
            <div class="card-header">
                Pengaturan Absensi
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="batas_telat" class="col-sm-2 col-form-label">Telat Maks. (menit)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="batas_telat" name="batas_telat"
                                value="<?php echo $batas_telat ?>">
                            <div class="form-text">Maksimal keterlambatan pengguna dalam melakukan absen masuk (menit).
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jarak" class="col-sm-2 col-form-label">Jarak Maks. (kilometer)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jarak" name="jarak"
                                value="<?php echo $jarak ?>">
                            <div class="form-text">Jarak maksimal pengguna dari sekolah (km).</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="latitude" class="col-sm-2 col-form-label">Latitude</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="latitude" name="latitude"
                                value="<?php echo $latitude ?>" required>
                            <div class="form-text">Latitude sekolah. Kamu bisa mengambilnya dari <i>maps.google.com</i>.
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="longitude" class="col-sm-2 col-form-label">Longitude</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="longitude" name="longitude"
                                value="<?php echo $longitude ?>" required>
                            <div class="form-text">Longitude sekolah. Kamu bisa mengambilnya dari
                                <i>maps.google.com</i>.
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Fitur Absen</label>
                        <div class="col-sm-10 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="switch_foto" name="fitur_foto" <?php echo $isFitur_foto; ?>>
                                <label class="form-check-label" for="switch_foto">Kamera Selfie Absensi</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk ganti password -->
        <div class="card" style="margin-bottom:50px">
            <div class="card-header" style="background:none">
                Form Ganti Password - <i>Abaikan jika tak perlu</i>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Password Lama</label>
                        <input class="form-control" type="password" name="oldpassword" required>
                    </div>
                    <div>
                        <label class="form-label">Password Baru</label>
                    </div>
                    <div class="input-group mb-3">
                        <input class="form-control" type="password" name="newpassword" id="password-input" required>
                        <span class="input-group-text" onclick="togglePb()"><i id="eye-icon"
                                class="bi bi-eye-slash"></i></span>
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                    </div>
                    <div class="input-group mb-3">
                        <input class="form-control" type="password" name="confirmpassword" id="confirm-password-input"
                            required>
                        <span class="input-group-text" onclick="toggleCp()"><i id="eye-icon2"
                                class="bi bi-eye-slash"></i></span>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="submit" value="Simpan" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function togglePb() {
            var passwordInput = document.getElementById("password-input");
            var eyeIcon = document.getElementById("eye-icon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }
        function toggleCp() {
            var conpasswordInput = document.getElementById("confirm-password-input");
            var eyeIcon = document.getElementById("eye-icon2");
            if (conpasswordInput.type === "password") {
                conpasswordInput.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                conpasswordInput.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }
    </script>
</body>

</html>