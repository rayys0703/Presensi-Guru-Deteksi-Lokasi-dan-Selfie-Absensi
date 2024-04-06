<?php
session_start();
require_once('cfgall.php');

function getJarakIdeal($conn)
{
	$stmt = $conn->prepare("SELECT jarak FROM pengaturan WHERE id_pengaturan = ?");
	$id_pengaturan = 1;
	$stmt->bindParam(1, $id_pengaturan);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$jarak_ideal = $result['jarak'];
	$stmt->closeCursor();

	return $jarak_ideal;
}

$stmt = $conn->prepare("SELECT * FROM jadwal WHERE nama_hari = ?");
$stmt->bindParam(1, $hari_ini);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($result) {
	$row = $result[0];
	$waktu_pulang = $row['waktu_pulang'];
} else {
	$waktu_pulang = date('H:i:s');
}

$waktu_pulang = date('H:i', strtotime($waktu_pulang)); // mengubah format waktu
$waktu_pulang = $waktu_pulang . " WIB"; // menambahkan "WIB" pada akhir string
$waktu_sekarang = date('H:i:s');
$waktu_sekarang = date('H:i', strtotime($waktu_sekarang)); // mengubah format waktu
$waktu_sekarang = $waktu_sekarang . " WIB"; // menambahkan "WIB" pada akhir string
$jarak_ideal = getJarakIdeal($conn);

// jika variabel bernilai kosong maka redirect ke login
if (empty($obj->get_idabsen($userid))) {
	echo
		'
			<script>
				swal.fire({
					title: "Gagal!",
					text: "Anda harus melakukan absen masuk terlebih dahulu",
					icon: "error",
				}).then((result) => {
					setTimeout(function () {
						window.location.href = "login";
					 }, 300);
				})
			</script>
			';
} else {
	if ($waktu_sekarang > $waktu_pulang) {

		if (isset($_POST['jarak'])) {
			$jarak = $_POST['jarak'];

			//Selanjutnya cek apakah sudah absen keluar sebelumnya
			if ($obj->cek_Absenkeluar($userid)) {
				echo
					'
				<script> 
					swal.fire({
						title: "Gagal!",
						text: "Anda sudah absen hari ini",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';
			} else {
				$jarak = floatval($jarak);
				$jarak_konv = floor($jarak);
				if ($jarak_konv <= $jarak_ideal) {
					$tgl_keluar = date('Y-m-d');
					$jam_keluar = date('H:i:s');

					if ($obj->update_Absenkeluar($tgl_keluar, $jam_keluar, $obj->id_absen)) {
						?>
						<script>
							swal.fire({
								title: "Berhasil!",
								text: "Anda berhasil absen keluar hari ini pada pukul <?php echo $waktu_sekarang ?>!",
								icon: "success",
							}).then((result) => {
								setTimeout(function () {
									window.location.href = "login";
								}, 300);
							})
						</script>
						<?php

					} else {
						echo
							'
				<script> 
					swal.fire({
						title: "Gagal!",
						text: "Anda gagal absen keluar hari ini!",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>
				';

					}
					//
				} else {
					echo '
	<script>
		swal.fire({
			title: "Gagal!",
			html: "Anda tidak berada pada lokasi<br>SMP dan SMA Pesantren MKGR Kertasemaya!<br><br>Jarak Anda: ' . $jarak . ' km",
			icon: "error",
		}).then((result) => {
			setTimeout(function () {
				window.location.href = "login";
			}, 300);
		});
	</script>';
				}
			}
		} else {
			echo '
		<script>
			swal.fire({
				title: "Gagal!",
				text: "Jarak tidak terdeteksi!",
				icon: "error",
			}).then((result) => {
				setTimeout(function () {
					window.location.href = "login";
				 }, 300);
			})
		</script>
	';
		}
	} else {
		?>
		<script>
			swal.fire({
				title: "Gagal!",
				text: "Hanya diperbolehkan keluar pada pukul <?php echo $waktu_pulang ?> atau lebih!",
				icon: "error",
			}).then((result) => {
				setTimeout(function () {
					window.location.href = "login";
				}, 300);
			})
		</script>
		<?php
	}
}
?>