<?php
session_start();
require "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);


$stmt = $con->prepare("
    SELECT 
        berita.*, 
        kategori.nama_kategori,
        users.username
    FROM berita
    JOIN kategori ON berita.id_kategori = kategori.id_kategori
    JOIN users ON berita.id_user = users.id
    WHERE berita.id_berita = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Berita tidak ditemukan";
    exit;
}

$berita = $result->fetch_assoc();

$con->query("UPDATE berita SET views = views + 1 WHERE id_berita = $id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($berita['judul']); ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background-color: #f4f6f9;
}
.article {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
}
.article img {
    max-height: 420px;
    width: 100%;
    object-fit: cover;
}
.meta {
    font-size: 14px;
    color: #6c757d;
}
</style>
</head>

<body>


<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a href="index.php" class="navbar-brand">
      📰 Portal Berita
    </a>
  </div>
</nav>


<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="article shadow-sm">

        <?php if (!empty($berita['gambar'])): ?>
          <img 
            src="../assets/img/<?= $berita['gambar']; ?>" 
            class="rounded mb-4"
            alt="<?= htmlspecialchars($berita['judul']); ?>">
        <?php endif; ?>

        <span class="badge bg-secondary mb-2">
          <?= $berita['nama_kategori']; ?>
        </span>

        <h2 class="fw-bold mt-2">
          <?= htmlspecialchars($berita['judul']); ?>
        </h2>

        <div class="meta mb-4">
          Oleh <strong><?= htmlspecialchars($berita['username']); ?></strong> ·
          <?= date("d M Y", strtotime($berita['tanggal'])); ?> ·
          👁 <?= $berita['views']; ?> views
        </div>

        <div class="content fs-6">
          <?= nl2br(htmlspecialchars($berita['isi'])); ?>
        </div>
        <div class="mt-4">
          <a href="dashboardUser.php" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Beranda
          </a>
        </div>

      </div>

    </div>
  </div>
</div>

</body>
</html>
