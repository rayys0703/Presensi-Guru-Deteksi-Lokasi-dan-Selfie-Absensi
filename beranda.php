<?php
// Rayya
session_start();
include_once 'cfgall.php';
?>
<!DOCTYPE html>
<html>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Dashboard | SMP dan SMA Pesantren MKGR Kertasemaya</title>
<style>
	.mx-auto {
		max-width: 800px !important
	}

	.card {
		margin-top: 10px;
	}
</style>

<body>
	<div class="kolomkanan">
		<div class="mx-auto">
			<div class="card mb-5 p-3">
				<div class="leftP">
					<div>
						<div class="card-body" style="padding-top:0">
							<h5 class="card-title">Selamat datang,</h5>
						</div>
					</div>
					<div class="profileIcon leftC flex solo" style="padding-bottom:20px">
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
					<p class='note'>Silakan klik menu Absen untuk mulai melakukan absensi. Pastikan akses lokasi sudah diizinkan ya!</p>
				</div>

				<div class="menu-beranda">
					<div class="item">
						<div class="sa">
							<a href="./absensi">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-calendar-plus"></i>
								</div>
								<strong>Absen</strong>
							</a>
						</div>
					</div>
					<div class="item">
						<div class="sa">
							<a href="./profil">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-person-lines-fill"></i>
								</div>
								<strong>Profil</strong>
							</a>
						</div>
					</div>
					<div class="item">
						<div class="sa">
							<a href="./riwayat">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-card-checklist"></i>
								</div>
								<strong>Riwayat</strong>
							</a>
						</div>
					</div>
					<div class="item">
						<div class="sa">
							<a href="./logout">
								<div class="icon-wrapper bg-putih">
									<i class="bi bi-box-arrow-left"></i>
								</div>
								<strong>Logout</strong>
							</a>
						</div>
					</div>
				</div>
			</div>

			<?php
			$kurun_waktu = 7; // 7 hari terakhir absen
			
			$tanggal_kurang = date('Y-m-d', strtotime('-' . $kurun_waktu . ' days'));

			$query = "SELECT absen.tanggal_absen, absen.jam_masuk, absen.jam_keluar, status_absen.nama_status, absen.keterangan
          FROM absen 
          JOIN status_absen ON absen.id_status = status_absen.id_status 
          WHERE absen.nip = ? AND absen.tanggal_absen >= ?
          ORDER BY absen.tanggal_absen DESC";

			$stmt = $conn->prepare($query);
			$stmt->execute([$userid, $tanggal_kurang]);
			$result = $stmt->fetchAll();

			if (count($result) > 0) { ?>
				<div class="card p-4 mb-5">
					<div class="card-body" style="padding-left:0;padding-bottom:0">
						<h5 class="card-title">Absensi 7 Hari Terakhir</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered table-striped mt-3">
							<thead>
								<tr>
									<th>Tanggal</th>
									<th>Masuk</th>
									<th>Keluar</th>
									<th>Status</th>
									<th>Keterangan</th>
								</tr>
							</thead>
							<tbody style="font-size:14px">
								<?php
								foreach ($result as $row) {
									$hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
									$bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

									$tanggal = date_create($row['tanggal_absen']);
									$hari_tanggal = $hari[date_format($tanggal, "w")];
									$tanggal_indo = $hari_tanggal . ", " . date_format($tanggal, "d") . " " . $bulan[date_format($tanggal, "m") - 1] . " " . date_format($tanggal, "Y");

									echo "<tr>";
									echo "<td>" . $tanggal_indo . "</td>";
									echo "<td>" . $row['jam_masuk'] . "</td>";
									echo "<td>" . $row['jam_keluar'] . "</td>";
									echo "<td>" . $row['nama_status'] . "</td>";
									echo "<td>" . $row['keterangan'] . "</td>";
									echo "</tr>";
								}

								function bulan($angka_bulan)
								{
									$nama_bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
									return $nama_bulan[intval($angka_bulan)];
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</body>

</html>