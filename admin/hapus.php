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

$stmt = $con->prepare("SELECT gambar FROM berita WHERE id_berita = ?");
$stmt->bind_param("i", $id_berita);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboardAdmin.php");
    exit;
}

$data = $result->fetch_assoc();
$gambar = $data['gambar'];


$path = "../assets/img/" . $gambar;
if ($gambar && file_exists($path)) {
    unlink($path);
}

$stmt = $con->prepare("DELETE FROM berita WHERE id_berita = ?");
$stmt->bind_param("i", $id_berita);
$stmt->execute();

header("Location: dashboardAdmin.php");
exit;
