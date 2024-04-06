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

function getBatasTelat($conn)
{
	$stmt = $conn->prepare("SELECT batas_telat, fitur_foto FROM pengaturan WHERE id_pengaturan = ?");
	$id_pengaturan = 1;
	$stmt->bindParam(1, $id_pengaturan);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$batas_telat = $result['batas_telat'];
	$stmt->closeCursor();

	return $batas_telat;
}

function getFiturFoto($conn)
{
	$stmt = $conn->prepare("SELECT fitur_foto FROM pengaturan WHERE id_pengaturan = ?");
	$id_pengaturan = 1;
	$stmt->bindParam(1, $id_pengaturan);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$fitur_foto = $result['fitur_foto'];
	$stmt->closeCursor();

	return $fitur_foto;
}

function getJadwal($conn, $hari_ini)
{
	$stmt = $conn->prepare("SELECT id_jadwal, status, waktu_masuk FROM jadwal WHERE nama_hari = ?");
	$stmt->bindParam(1, $hari_ini);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$id_jadwal = $result['id_jadwal'];
	$status = $result['status'];
	$waktu_masuk = $result['waktu_masuk'];
	$stmt->closeCursor();

	return array(
		'id_jadwal' => $id_jadwal,
		'status' => $status,
		'waktu_masuk' => $waktu_masuk
	);
}

function hitungSelisihMenit($waktu_masuk, $jam_masuk)
{
	$waktu_masuk = strtotime($waktu_masuk);
	$jam_masuk = strtotime($jam_masuk);
	$selisih_menit = round(($jam_masuk - $waktu_masuk) / 60);

	return $selisih_menit;
}

// Pengambilan FUNGSI
$jarak_ideal = getJarakIdeal($conn);
$batas_telat = getBatasTelat($conn);
$fitur_foto = getFiturFoto($conn);

$tanggal_absen = date('Y-m-d');
$jam_masuk = date('H:i:s');

$jadwal = getJadwal($conn, $hari_ini);
$id_jadwal = $jadwal['id_jadwal'];
$status = $jadwal['status'];
$waktu_masuk = $jadwal['waktu_masuk'];

$selisih_menit = hitungSelisihMenit($waktu_masuk, $jam_masuk);

if ($status == 'Aktif') {
	if (isset($_POST['jarak'], $_POST['latlong'])) {
		if ($selisih_menit <= $batas_telat) {
			$jarak = $_POST['jarak'];
			$latlong = $_POST['latlong'];

			if (isset($_POST['photo']) && $fitur_foto == 1) {
				$compressedPhoto = $_POST['photo'];
			}

			# Cek apakah dia sudah absen sebelumnya
			if ($obj->cek_Absenmasuk($userid)) {
				echo
					'
					<script> 
						swal.fire({
							title: "Gagal!",
							text: "Anda sudah absen hari ini",
							icon: "error",
						}).then((result) => {
							setTimeout(function () {
								window.location.href = "beranda";
							 }, 300);
						})
					</script>
					';
			} else {
				$jarak = floatval($jarak);
				$jarak_konv = floor($jarak);
				if ($jarak_konv <= $jarak_ideal) { // jarak maksimal (km) agar bisa masuk
					$id_status = 1; // 1 yaitu MASUK

					if ($fitur_foto == 1) {
						$decodedPhoto = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $compressedPhoto));
						$targetDirectory = "hasil_absen/";
						$file_foto = $userid . "_" . date('Y-m-d') . ".png";
						$targetPath = $targetDirectory . $file_foto;
						file_put_contents($targetPath, $decodedPhoto);
					} else {
						$file_foto = '';
					}

					$sqlW = "SELECT waktu_masuk FROM jadwal WHERE id_jadwal = ?";
					$stmtW = $conn->prepare($sqlW);
					$stmtW->bindParam(1, $id_jadwal);
					$stmtW->execute();
					$resultW = $stmtW->fetch(PDO::FETCH_ASSOC);

					if ($resultW) {
						$waktu_masuk = $resultW['waktu_masuk'];
					} else {
						$waktu_masuk = date('H:i:s');
					}

					$stmtW->closeCursor();

					$jam_masuk = date('H:i:s');

					// menghitung selisih waktu
					$tanggal_waktu_target = date('Y-m-d') . $waktu_masuk;
					$selisih_waktu = strtotime($jam_masuk) - strtotime($tanggal_waktu_target);

					// jika selisih waktu lebih dari 0 (terlambat)
					if ($selisih_waktu > 0) {
						if ($selisih_waktu < 3600) {
							$menit_terlambat = ceil($selisih_waktu / 60); // menghitung selisih waktu dalam menit
							if ($menit_terlambat == 60) {
								$keterangan = $jarak . ' kilometer' . ", TERLAMBAT 1 jam";
							} else {
								$keterangan = $jarak . ' kilometer' . ", TERLAMBAT $menit_terlambat menit";
							}
						} else {
							$jam_terlambat = floor($selisih_waktu / 3600); // menghitung selisih waktu dalam jam
							$menit_terlambat = ceil(($selisih_waktu % 3600) / 60); // menghitung selisih waktu dalam menit
							if ($menit_terlambat == 60) {
								$jam_terlambat++; // jika terdapat 60 menit, tambahkan 1 jam ke jumlah jam terlambat
								$menit_terlambat = 0; // reset jumlah menit terlambat menjadi 0
							}
							if ($jam_terlambat == 1) {
								$keterangan = $jarak . ' kilometer' . ", TERLAMBAT 1 jam $menit_terlambat menit";
							} else {
								$keterangan = $jarak . ' kilometer' . ", TERLAMBAT $jam_terlambat jam $menit_terlambat menit";
							}
						}
					} else {
						$keterangan = $jarak . ' kilometer';
					}
					// eksekusi absen masuk
					if ($obj->insert_Absenmasuk($userid, $id_status, $id_jadwal, $tanggal_absen, $jam_masuk, $keterangan, $file_foto, $latlong)) {
						echo
							'
					<script> 
						swal.fire({
							title: "Berhasil Masuk!",
							html: "JARAK ' . $keterangan . '",
							icon: "success",
						}).then((result) => {
							setTimeout(function () {
								window.location.href = "login";
							 }, 300);
						})
					</script>
					';

					} else {
						echo
							'
					<script> 
						swal.fire({
							title: "Gagal!",
							text: "Anda gagal absen hari ini!",
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
					echo '
				<script>
					swal.fire({
						title: "Gagal!",
						text: "Anda tidak berada pada lokasi SMP SMA MKGR Kertasemaya!",
						icon: "error",
					}).then((result) => {
						setTimeout(function () {
							window.location.href = "login";
						 }, 300);
					})
				</script>';
				}
			}
		} else {
			?>
			<script>
				swal.fire({
					title: "Gagal!",
					text: "Anda sudah melebihi batas terlambat (<?php echo $batas_telat ?> menit)!",
					icon: "error",
				}).then((result) => {
					setTimeout(function () {
						window.location.href = "login";
					}, 300);
				})
			</script>
			<?php
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
	echo '
		<script>
			swal.fire({
				title: "Gagal!",
				text: "Hari Ini Hari Libur!",
				icon: "error",
			}).then((result) => {
				setTimeout(function () {
					window.location.href = "login";
				 }, 300);
			})
		</script>
	';
}
?>