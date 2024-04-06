-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Apr 2024 pada 08.45
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absen`
--

CREATE TABLE `absen` (
  `id_absen` int(11) NOT NULL,
  `nip` int(11) NOT NULL,
  `id_status` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `tanggal_absen` date DEFAULT NULL,
  `jam_masuk` time DEFAULT NULL,
  `tgl_keluar` date DEFAULT NULL,
  `jam_keluar` time DEFAULT NULL,
  `keterangan` varchar(55) DEFAULT NULL,
  `foto_absen` varchar(255) DEFAULT NULL,
  `latlong` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `jabatan_id` int(11) NOT NULL,
  `jabatan_nama` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`jabatan_id`, `jabatan_nama`) VALUES
(1, 'Guru'),
(2, 'Tata Usaha'),
(3, 'BPH');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `nama_hari` varchar(11) NOT NULL,
  `waktu_masuk` time NOT NULL,
  `waktu_pulang` time NOT NULL,
  `status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jadwal`
--

INSERT INTO `jadwal` (`id_jadwal`, `nama_hari`, `waktu_masuk`, `waktu_pulang`, `status`) VALUES
(1, 'Senin', '07:45:00', '12:45:00', 'Aktif'),
(2, 'Selasa', '07:00:00', '12:00:00', 'Aktif'),
(3, 'Rabu', '08:00:00', '12:00:00', 'Aktif'),
(4, 'Kamis', '07:15:00', '12:45:00', 'Aktif'),
(5, 'Jumat', '07:15:00', '12:45:00', 'Aktif'),
(6, 'Sabtu', '07:15:00', '12:45:00', 'Aktif'),
(7, 'Minggu', '00:00:00', '00:00:00', 'Nonaktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penempatan`
--

CREATE TABLE `penempatan` (
  `penempatan_id` int(11) NOT NULL,
  `penempatan_nama` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penempatan`
--

INSERT INTO `penempatan` (`penempatan_id`, `penempatan_nama`) VALUES
(1, 'SMP'),
(2, 'SMA'),
(3, 'SMP SMA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_pengaturan` int(11) NOT NULL,
  `batas_telat` int(11) NOT NULL,
  `jarak` int(11) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `fitur_foto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id_pengaturan`, `batas_telat`, `jarak`, `latitude`, `longitude`, `fitur_foto`) VALUES
(1, 60, 35, '-6.500362442242321', '108.36465238076016', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nip` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jabatan_id` int(11) NOT NULL,
  `penempatan_id` int(11) NOT NULL,
  `foto_profil` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nip`, `nama`, `password`, `jabatan_id`, `penempatan_id`, `foto_profil`) VALUES
(1, 123, 'Qurrotu Aini', '202cb962ac59075b964b07152d234b70', 1, 3, '123_64906dae62380.jpg'),
(2, 124, 'Hilal SF', 'c8ffe9a587b126f152ed3d89a146b445', 2, 3, '124_2203072.jpg'),
(3, 125, 'Rayya R', '3def184ad8f4755ff269862ea77393dd', 1, 2, '125_648cfea60b653.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `status_absen`
--

CREATE TABLE `status_absen` (
  `id_status` int(11) NOT NULL,
  `nama_status` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `status_absen`
--

INSERT INTO `status_absen` (`id_status`, `nama_status`) VALUES
(1, 'Hadir'),
(2, 'Izin'),
(3, 'Sakit'),
(4, 'Cuti');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absen`
--
ALTER TABLE `absen`
  ADD PRIMARY KEY (`id_absen`),
  ADD KEY `nip` (`nip`),
  ADD KEY `id_jadwal` (`id_jadwal`),
  ADD KEY `id_status` (`id_status`);

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`jabatan_id`),
  ADD KEY `jabatan_id` (`jabatan_id`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `penempatan`
--
ALTER TABLE `penempatan`
  ADD PRIMARY KEY (`penempatan_id`),
  ADD KEY `penempatan_id` (`penempatan_id`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_pengaturan`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip` (`nip`),
  ADD KEY `jabatan_id` (`jabatan_id`),
  ADD KEY `penempatan_id` (`penempatan_id`);

--
-- Indeks untuk tabel `status_absen`
--
ALTER TABLE `status_absen`
  ADD PRIMARY KEY (`id_status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absen`
--
ALTER TABLE `absen`
  MODIFY `id_absen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=227;

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `jabatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `penempatan`
--
ALTER TABLE `penempatan`
  MODIFY `penempatan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `status_absen`
--
ALTER TABLE `status_absen`
  MODIFY `id_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absen`
--
ALTER TABLE `absen`
  ADD CONSTRAINT `absen_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `status_absen` (`id_status`),
  ADD CONSTRAINT `absen_ibfk_2` FOREIGN KEY (`nip`) REFERENCES `pengguna` (`nip`),
  ADD CONSTRAINT `absen_ibfk_3` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`);

--
-- Ketidakleluasaan untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`jabatan_id`),
  ADD CONSTRAINT `pengguna_ibfk_2` FOREIGN KEY (`penempatan_id`) REFERENCES `penempatan` (`penempatan_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
