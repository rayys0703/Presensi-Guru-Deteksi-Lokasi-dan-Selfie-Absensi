<?php
session_start();

include_once 'main-admin.php';

$id_jadwal = "";
$nama_hari = "";
$waktu_masuk = "";
$waktu_pulang = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'simpan') {
    $id_jadwal = $_POST['editId'];
    $waktu_masuk = $_POST['editWaktumasuk'];
    $waktu_pulang = $_POST['editWaktupulang'];
    $status = $_POST['editStatus'];

    if ($id_jadwal && $waktu_masuk && $waktu_pulang) {
        $sql = "UPDATE jadwal SET waktu_masuk = :waktu_masuk, waktu_pulang = :waktu_pulang, status = :status WHERE id_jadwal = :id_jadwal";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':waktu_masuk', $waktu_masuk);
        $stmt->bindParam(':waktu_pulang', $waktu_pulang);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id_jadwal', $id_jadwal);
        $stmt->execute();
        
        $sukses = "Data berhasil diupdate";
    } else {
        $error = "Silakan masukkan semua data";
    }
}

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
    <title>Data Jadwal</title>
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
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="?op=simpan" method="POST">
                            <div class="mb-3">
                                <label for="editHari" class="form-label">Hari</label>
                                <input type="text" class="form-control" id="editHari" name="editHari" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="editWaktumasuk" class="form-label">Waktu Masuk</label>
                                <input type="time" class="form-control" id="editWaktumasuk" name="editWaktumasuk"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editWaktupulang" class="form-label">Waktu Pulang</label>
                                <input type="time" class="form-control" id="editWaktupulang" name="editWaktupulang"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Status</label>
                                <select class="form-select" id="editStatus" name="editStatus" required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>

                            <input type="hidden" id="editId" name="editId">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header">
                Data Jadwal
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Waktu Masuk</th>
                            <th scope="col">Waktu Pulang</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM jadwal";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $urut = 1;
                        
                        foreach ($result as $row) {
                            $id_jadwal = $row['id_jadwal'];
                            $nama_hari = $row['nama_hari'];
                            $waktu_masuk = $row['waktu_masuk'];
                            $waktu_pulang = $row['waktu_pulang'];
                            $status = $row['status'];
                            ?>
                            <tr>
                                <td scope="row">
                                    <?php echo $id_jadwal ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama_hari ?>
                                </td>
                                <td scope="row">
                                    <?php echo $waktu_masuk ?>
                                </td>
                                <td scope="row">
                                    <?php echo $waktu_pulang ?>
                                </td>
                                <td scope="row">
                                    <?php if ($status == "Nonaktif") { ?>
                                        <span class='text-danger'><?php echo $status ?></span>
                                    <?php } else { ?>
                                        <span><?php echo $status ?></span>
                                    <?php } ?>
                                </td>
                                <td scope="row">
                                    <a data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-jadwal="<?php echo $id_jadwal ?>" data-namahari="<?php echo $nama_hari ?>"
                                        data-waktumasuk="<?php echo $waktu_masuk ?>"
                                        data-waktupulang="<?php echo $waktu_pulang ?>" data-status="<?php echo $status ?>">
                                        <button type="button" class="btn btn-warning btn-sm">Edit</button>
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }                        
                        ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tables = document.querySelectorAll('.table');
            tables.forEach(function (table) {
                new DataTable(table);
            });
        });

        var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            keyboard: false
        });

        var editButtons = document.querySelectorAll('a[data-bs-toggle="modal"]');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var id_jadwal = this.getAttribute('data-jadwal');
                var namahari = this.getAttribute('data-namahari');
                var waktu_masuk = this.getAttribute('data-waktumasuk');
                var waktu_pulang = this.getAttribute('data-waktupulang');
                var status = this.getAttribute('data-status');

                document.getElementById('editId').value = id_jadwal;
                document.getElementById('editHari').value = namahari;
                document.getElementById('editWaktumasuk').value = waktu_masuk;
                document.getElementById('editWaktupulang').value = waktu_pulang;
                var editStatusSelect = document.getElementById('editStatus');
                for (var i = 0; i < editStatusSelect.options.length; i++) {
                    if (editStatusSelect.options[i].value === status) {
                        editStatusSelect.options[i].selected = true;
                        break;
                    }
                }

                editModal.show();
            });
        });
    </script>
</body>

</html>