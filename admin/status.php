<?php
session_start();

include_once 'main-admin.php';

$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'simpan') {
    $id_status = $_POST['editId'];
    $nama_status = $_POST['editNama'];

    if ($id_status && $nama_status) {
        // Periksa apakah nama_status sudah ada sebelumnya
        $sql_check = "SELECT COUNT(*) FROM status_absen WHERE nama_status = :nama_status";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':nama_status', $nama_status);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            $error = "Keterangan Status sudah ada!";
        } else {
            $sql_update = "UPDATE status_absen SET nama_status = :nama_status WHERE id_status = :id_status";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':nama_status', $nama_status);
            $stmt_update->bindParam(':id_status', $id_status);
            $stmt_update->execute();
            $sukses = "Data berhasil diupdate";
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
    <title>Data Status Absen</title>
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

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header">
                Data Status Absen
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Keterangan Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM status_absen";
                        $stmt = $conn->prepare($sql2);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $urut = 1;
                        foreach ($result as $r2) {
                            $id_status = $r2['id_status'];
                            $nama_status = $r2['nama_status'];
                            ?>
                            <tr>
                                <td scope="row">
                                    <?php echo $id_status ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama_status ?>
                                </td>
                                <td scope="row">
                                    <?php if ($id_status != 1) {
                                        ?><a data-bs-toggle="modal" data-bs-target="#editModal"
                                            data-id="<?php echo $id_status ?>" data-nama="<?php echo $nama_status ?>">
                                            <button type="button" class="btn btn-warning">Edit</button>
                                        </a>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-warning" disabled>Edit</button>
                                    <?php } ?>
                                    <!--<a href="?op=delete&id=<?php echo $id_status ?>"
                                        onclick="return confirm('Yakin mau delete data?')"><button type="button"
                                            class="btn btn-danger">Delete</button></a>-->
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var tables = document.querySelectorAll('.table');
                        tables.forEach(function (table) {
                            new DataTable(table);
                        });
                    });

                    var editButtons = document.querySelectorAll('a[data-bs-toggle="modal"]');
                    editButtons.forEach(function (button) {
                        button.addEventListener('click', function () {
                            var id_status = this.getAttribute('data-id');
                            var nama_status = this.getAttribute('data-nama');

                            document.getElementById('editId').value = id_status;
                            document.getElementById('editNama').value = nama_status;

                            editModal.show();
                        });
                    });
                </script>
            </div>
        </div>

        <!-- Modal Edit Status -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Status Absen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="?op=simpan" method="POST">
                            <div class="mb-3">
                                <label for="editNama" class="form-label">Keterangan Status</label>
                                <input type="text" class="form-control" id="editNama" name="editNama" required>
                            </div>

                            <input type="hidden" id="editId" name="editId">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>