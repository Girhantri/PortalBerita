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
$error = "";

$stmt = $con->prepare("
    SELECT b.*, k.nama_kategori 
    FROM berita b 
    JOIN kategori k ON b.id_kategori = k.id_kategori
    WHERE b.id_berita = ?
");

$stmt->bind_param("i", $id_berita);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboardAdmin.php");
    exit;
}

$berita = $result->fetch_assoc();

/* SUBMIT UPDATE */
if (isset($_POST['update'])) {

    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);
    $kategori_input = trim($_POST['kategori']);

    if ($judul === "") {
        $error = "Judul wajib diisi";
    } elseif (strlen($isi) < 50) {
        $error = "Isi berita minimal 50 karakter";
    } elseif ($kategori_input === "") {
        $error = "Kategori wajib diisi";
    } else {

        /* CEK / INSERT KATEGORI */
        $stmt = $con->prepare("SELECT id_kategori FROM kategori WHERE nama_kategori = ?");
        $stmt->bind_param("s", $kategori_input);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $id_kategori = $row['id_kategori'];
        } else {
            $stmt = $con->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
            $stmt->bind_param("s", $kategori_input);
            $stmt->execute();
            $id_kategori = $stmt->insert_id;
        }

        /* HANDLE GAMBAR */
        $namaGambar = $berita['gambar'];

        if (!empty($_FILES['gambar']['name'])) {

            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png'];

            if (!in_array($ext, $allowed)) {
                $error = "Format gambar harus JPG / PNG";
            } else {

                $namaGambar = time() . "_" . $_FILES['gambar']['name'];
                move_uploaded_file(
                    $_FILES['gambar']['tmp_name'],
                    "../assets/img/" . $namaGambar
                );

                // hapus gambar lama
                if (file_exists("../assets/img/" . $berita['gambar'])) {
                    unlink("../assets/img/" . $berita['gambar']);
                }
            }
        }

        if ($error === "") {

            $stmt = $con->prepare("
                UPDATE berita 
                SET judul=?, isi=?, id_kategori=?, gambar=?
                WHERE id_berita=?
            ");
            $stmt->bind_param(
                "ssisi",
                $judul,
                $isi,
                $id_kategori,
                $namaGambar,
                $id_berita
            );
            $stmt->execute();

            header("Location: dashboardAdmin.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Berita</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }
.card { border-radius: 14px; }
</style>
</head>
<body>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card shadow-sm">
        <div class="card-header fw-semibold">
          <i class="bi bi-pencil-square"></i> Edit Berita
        </div>

        <div class="card-body">

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
              <label class="form-label">Judul</label>
              <input type="text" name="judul" class="form-control"
                     value="<?= htmlspecialchars($berita['judul']) ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Isi Berita</label>
              <textarea name="isi" rows="5" class="form-control"><?= htmlspecialchars($berita['isi']) ?></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <input type="text" name="kategori" class="form-control"
                     value="<?= htmlspecialchars($berita['nama_kategori']) ?>">
            </div>

            <div class="mb-3">
              <label class="form-label">Gambar Lama</label><br>
              <img src="../assets/img/<?= $berita['gambar'] ?>" width="180" class="rounded mb-2">
            </div>

            <div class="mb-3">
              <label class="form-label">Ganti Gambar (opsional)</label>
              <input type="file" name="gambar" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
              <a href="dashboardAdmin.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
              <button type="submit" name="update" class="btn btn-primary">
                <i class="bi bi-save"></i> Update
              </button>
            </div>

          </form>

        </div>
      </div>
