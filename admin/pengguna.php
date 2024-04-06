<?php
session_start();

include_once 'main-admin.php';

$nip = "";
$password = "";
$nama = "";
$jabatan = "";
$penempatan = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'hapus') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM pengguna WHERE id = :id";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();
    if ($stmt1->rowCount() > 0) {
        $sukses = "Berhasil hapus pengguna";
    } else {
        $error = "Gagal melakukan hapus pengguna";
    }
}

if ($op == 'tambah') {
    $nip = $_POST['tambahNip'];
    $nama = $_POST['tambahNama'];
    $password = md5($_POST['tambahPassword']);
    $jabatan = $_POST['tambahJabatan'];
    $penempatan = $_POST['tambahPenempatan'];

    $sql_cek_nip = "SELECT * FROM pengguna WHERE nip=:nip";
    $stmt_cek_nip = $conn->prepare($sql_cek_nip);
    $stmt_cek_nip->bindParam(':nip', $nip, PDO::PARAM_STR);
    $stmt_cek_nip->execute();
    $jml_cek_nip = $stmt_cek_nip->rowCount();

    if ($jml_cek_nip > 0) {
        $error = "NIP sudah terdaftar!";
    } else {
        if ($nip && $password && $nama && $jabatan && $penempatan) {
            $sql1 = "INSERT INTO pengguna (nip, nama, password, jabatan_id, penempatan_id) VALUES (:nip, :nama, :password, :jabatan, :penempatan)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':nip', $nip, PDO::PARAM_STR);
            $stmt1->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt1->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt1->bindParam(':jabatan', $jabatan, PDO::PARAM_INT);
            $stmt1->bindParam(':penempatan', $penempatan, PDO::PARAM_INT);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0) {
                $sukses = "Berhasil menambah data pengguna: $nama";
            } else {
                $error = "Gagal menambah data pengguna";
            }
        } else {
            $error = "Silakan masukkan semua data";
        }
    }
}

if ($op == 'simpan') {
    $nip = $_POST['editNip'];
    $nama = $_POST['editNama'];
    $password = $_POST['editPassword'];
    $jabatan = $_POST['editJabatan'];
    $penempatan = $_POST['editPenempatan'];

    if ($nama && $jabatan && $penempatan) {
        if ($password && trim($password) !== '') {
            $password = md5($password);
            $sql1 = "UPDATE pengguna SET nama=:nama, password=:password, jabatan_id=:jabatan, penempatan_id=:penempatan WHERE nip=:nip";
        } else {
            // Jika password kosong atau hanya berisi spasi
            $sql1 = "UPDATE pengguna SET nama=:nama, jabatan_id=:jabatan, penempatan_id=:penempatan WHERE nip=:nip";
        }

        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(':nama', $nama, PDO::PARAM_STR);
        $stmt1->bindParam(':jabatan', $jabatan, PDO::PARAM_INT);
        $stmt1->bindParam(':penempatan', $penempatan, PDO::PARAM_INT);
        $stmt1->bindParam(':nip', $nip, PDO::PARAM_STR);
        if ($password && trim($password) !== '') {
            $stmt1->bindParam(':password', $password, PDO::PARAM_STR);
        }
        $stmt1->execute();

        if ($stmt1->rowCount() > 0) {
            $sukses = "Data berhasil diupdate untuk $nama";
        } else {
            $error = "Data gagal diupdate";
        }
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
    <title>Data Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            max-width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Edit Data Pengguna -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=simpan" method="POST">
                        <div class="mb-3 d-none">
                            <label for="editNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="editNip" name="editNip">
                        </div>
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="editNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="editPassword">
                            <div class="form-text">Biarkan kosong jika tak ingin mengubahnya.</div>
                        </div>
                        <div class="mb-3">
                            <?php
                            $sqljabatan = "SELECT * FROM jabatan";
                            $stmt = $conn->prepare($sqljabatan);
                            $stmt->execute();

                            $option_jabatan = '';
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $option_jabatan .= '<option value="' . $row['jabatan_id'] . '">' . $row['jabatan_nama'] . '</option>';
                            }
                            ?>
                            <label for="editJabatan" class="form-label">Jabatan</label>
                            <div>
                                <select class="form-select" name="editJabatan" id="editJabatan" required>
                                    <option value="">- Pilih Jabatan -</option>
                                    <?php echo $option_jabatan ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <?php
                            $sqlpenempatan = "SELECT * FROM penempatan";
                            $stmt = $conn->prepare($sqlpenempatan);
                            $stmt->execute();

                            $option_penempatan = '';
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $option_penempatan .= '<option value="' . $row['penempatan_id'] . '">' . $row['penempatan_nama'] . '</option>';
                            }
                            ?>
                            <label for="editPenempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="editPenempatan" name="editPenempatan" required>
                                <option value="">- Pilih Penempatan -</option>
                                <?php echo $option_penempatan ?>
                            </select>
                        </div>

                        <input type="hidden" id="editId" name="editId">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Data Pengguna -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=tambah" method="POST">
                        <div class="mb-3">
                            <label for="tambahNip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="tambahNip" name="tambahNip" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="tambahNama" name="tambahNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="tambahPassword" name="tambahPassword"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahJabatan" class="form-label">Jabatan</label>
                            <div>
                                <select class="form-select" name="tambahJabatan" id="tambahJabatan" required>
                                    <option value="">- Pilih Jabatan -</option>
                                    <?php echo $option_jabatan ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPenempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="tambahPenempatan" name="tambahPenempatan" required>
                                <option value="">- Pilih Penempatan -</option>
                                <?php echo $option_penempatan ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto mb-5">
        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex align-items-center">Data Pengguna</div>
                <a data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <button type="button" class="btn btn-primary">Tambah Pengguna</button>
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <!--<th scope="col">No</th>-->
                            <th scope="col">NIP</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Penempatan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT pengguna.id, pengguna.nip, pengguna.nama, pengguna.foto_profil, pengguna.password, jabatan.jabatan_id, jabatan.jabatan_nama, penempatan.penempatan_id, penempatan.penempatan_nama
                        FROM pengguna
                        INNER JOIN jabatan ON pengguna.jabatan_id = jabatan.jabatan_id
                        INNER JOIN penempatan ON pengguna.penempatan_id = penempatan.penempatan_id
                        ORDER BY pengguna.nip ASC";

                        $stmt = $conn->prepare($sql2);
                        $stmt->execute();
                        $urut = 1;

                        while ($r2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $id = $r2['id'];
                            $nip = $r2['nip'];
                            $nama = $r2['nama'];
                            if ($r2['foto_profil'] == NULL) {
                                $nama_file = "default.png";
                            } else {
                                $nama_file = $r2['foto_profil'];
                                $path_to_file = "../foto_profil/" . $nama_file;
                                if (!file_exists($path_to_file)) {
                                    $nama_file = "default.png";
                                }
                            }
                            $password = '';
                            $id_jabatan = $r2['jabatan_id'];
                            $jabatan = $r2['jabatan_nama'];
                            $id_penempatan = $r2['penempatan_id'];
                            $penempatan = $r2['penempatan_nama'];

                            ?>
                            <tr>
                                <!--<th scope="row">
                                    <?php echo $urut++ ?>
                                </th>-->
                                <td scope="row">
                                    <b>
                                        <?php echo $nip ?>
                                    </b>
                                </td>
                                <td scope="row">
                                    <div style="imgBx"><img src="../foto_profil/<?php echo $nama_file; ?>" alt="" width="32"
                                            height="32" style="border-radius:50%"></div>
                                </td>
                                <td scope="row">
                                    <?php echo $nama ?>
                                </td>
                                <td scope="row">
                                    <?php echo $jabatan ?>
                                </td>
                                <td scope="row">
                                    <?php echo $penempatan ?>
                                </td>
                                <td scope="row">
                                    <a id="iniEditModal" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $id ?>" data-nip="<?php echo $nip ?>"
                                        data-nama="<?php echo $nama ?>" data-password="<?php echo $password ?>"
                                        data-jabatan="<?php echo $id_jabatan ?>" data-penempatan="<?php echo $id_penempatan ?>">
                                        <button type="button" class="btn btn-warning btn-sm">Edit</button>
                                    </a>
                                    <button type='button'
                                        onclick='return confirmDelete(`<?php echo $id ?>`,`<?php echo $nama ?>`)'
                                        class='btn btn-danger btn-sm'>Hapus</button>
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

        function confirmDelete(id, nama) {
            Swal.fire({
                title: "Konfirmasi",
                html: "Apakah Anda yakin ingin menghapus <b>`" + nama + "`</b>?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?op=hapus&id=" + id;
                }
            });

            return false;
        }

        var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            keyboard: false
        });

        var editButtons = document.querySelectorAll('a[id="iniEditModal"]');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var nip = this.getAttribute('data-nip');
                var nama = this.getAttribute('data-nama');
                var password = this.getAttribute('data-password');
                var jabatan = this.getAttribute('data-jabatan');
                var penempatan = this.getAttribute('data-penempatan');

                document.getElementById('editId').value = id;
                document.getElementById('editNip').value = nip;
                document.getElementById('editNama').value = nama;
                document.getElementById('editPassword').value = password;

                var editJabatanSelect = document.getElementById('editJabatan');
                for (var i = 0; i < editJabatanSelect.options.length; i++) {
                    if (editJabatanSelect.options[i].value === jabatan) {
                        editJabatanSelect.options[i].selected = true;
                        break;
                    }
                }

                var editPenempatanSelect = document.getElementById('editPenempatan');
                for (var i = 0; i < editPenempatanSelect.options.length; i++) {
                    if (editPenempatanSelect.options[i].value === penempatan) {
                        editPenempatanSelect.options[i].selected = true;
                        break;
                    }
                }

                editModal.show();
            });
        });
    </script>
</body>

</html>