<?php
session_start();
require "../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: dashboardAdmin.php");
    exit;
}

$id_berita = intval($_GET['id']);

$ambil_gambar = $con->prepare("SELECT gambar FROM berita WHERE id_berita = ?");
$ambil_gambar->bind_param("i", $id_berita);
$ambil_gambar->execute();
$hasil_cari = $ambil_gambar->get_result();

if ($hasil_cari->num_rows === 0) {
    header("Location: dashboardAdmin.php");
    exit;
}

$data = $hasil_cari->fetch_assoc();
$nama_file_gambar = $data['gambar'];

$path_folder = "../assets/img/" . $nama_file_gambar;
if ($nama_file_gambar && file_exists($path_folder)) {
    unlink($path_folder); 
}

$eksekusi_hapus = $con->prepare("DELETE FROM berita WHERE id_berita = ?");
$eksekusi_hapus->bind_param("i", $id_berita);
$eksekusi_hapus->execute();

header("Location: dashboardAdmin.php");
exit;
?>