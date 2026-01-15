<?php
session_start();
require "../config/koneksi.php";

$keyword = $_GET['search'] ?? "";

if ($keyword) {

    $ambil_berita = $con->prepare("
        SELECT b.*, k.nama_kategori
        FROM berita b
        JOIN kategori k ON b.id_kategori = k.id_kategori
        WHERE b.judul LIKE ?
        ORDER BY b.tanggal DESC
    ");

    $like = "%$keyword%";
    $ambil_berita->bind_param("s", $like);
} else {

    $ambil_berita = $con->prepare("
        SELECT b.*, k.nama_kategori
        FROM berita b
        JOIN kategori k ON b.id_kategori = k.id_kategori
        ORDER BY b.tanggal DESC
    ");
}

$ambil_berita->execute();
$result = $ambil_berita->get_result();

$popular = $con->query("
    SELECT id_berita, judul, views
    FROM berita
    ORDER BY views DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Portal Berita</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { background: #f4f6f9; }
.news-item { padding-bottom: 20px; border-bottom: 1px solid #ddd; }
</style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container d-flex justify-content-between">
    <span class="navbar-brand">📰 Portal Berita</span>

    <?php if(isset($_SESSION['login'])): ?>
      <a href="../auth/logout.php" 
         class="btn btn-outline-light btn-sm">
        <i class="bi bi-box-arrow-right"></i> Logout
      </a>
    <?php endif; ?>
  </div>
</nav>


<div class="container mt-4">
  <div class="row">

    <div class="col-md-8">

      <form class="mb-3">
        <div class="input-group">
          <input type="text" name="search" class="form-control"
                 placeholder="Cari berita..."
                 value="<?= htmlspecialchars($keyword); ?>">
          <button class="btn btn-dark">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>

      <?php while($row = $result->fetch_assoc()): ?>
      <div class="news-item mb-4">

        <span class="badge bg-secondary mb-1">
          <?= $row['nama_kategori']; ?>
        </span>

        <h4 class="mt-2">
          <?= htmlspecialchars($row['judul']); ?>
        </h4>

        <div class="text-muted small mb-2">
          <i class="bi bi-calendar"></i>
          <?= date('d M Y', strtotime($row['tanggal'])); ?>
        </div>

        <p>
          <?= substr(strip_tags($row['isi']), 0, 200); ?>...
        </p>

        <a href="detail.php?id=<?= $row['id_berita']; ?>"
           class="text-decoration-none fw-semibold">
          Baca selengkapnya →
        </a>

      </div>
      <?php endwhile; ?>

    </div>


    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">
           Berita Populer
        </div>
        <div class="card-body">

          <?php while($p = $popular->fetch_assoc()): ?>
            <div class="mb-3">
              <a href="detail.php?id=<?= $p['id_berita']; ?>"
                 class="text-dark text-decoration-none">
                <?= htmlspecialchars($p['judul']); ?>
              </a>
              <div class="small text-muted">
                <i class="bi bi-eye"></i> <?= $p['views']; ?> views
              </div>
            </div>
          <?php endwhile; ?>

        </div>
      </div>
    </div>

  </div>
</div>

</body>
</html>
