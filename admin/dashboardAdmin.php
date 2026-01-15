<?php
session_start();
require "../config/koneksi.php";
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$query = "
  SELECT 
    b.id_berita,
    b.judul,
    b.tanggal,
    b.views,
    k.nama_kategori
  FROM berita b
  LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
  ORDER BY b.id_berita DESC
";

$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
  --primary: #2563eb;
  --bg: #f1f5f9;
  --card: #ffffff;
  --text: #0f172a;
  --muted: #64748b;
}

body {
  background-color: var(--bg);
  font-family: 'Inter', system-ui, sans-serif;
  color: var(--text);
}
.navbar {
  background: #0f172a;
}
.navbar-brand {
  font-weight: 600;
  letter-spacing: .5px;
}
.card {
  border: none;
  border-radius: 14px;
  box-shadow: 0 10px 25px rgba(0,0,0,.05);
}

.table thead th {
  font-size: 14px;
  text-transform: uppercase;
  color: var(--muted);
  border-bottom: 1px solid #e2e8f0;
}
.table td {
  vertical-align: middle;
}

.btn-primary {
  background: var(--primary);
  border: none;
}
.btn-primary:hover {
  background: #1d4ed8;
}

.badge-category {
  background: #e0e7ff;
  color: #1e40af;
  font-weight: 500;
}


.btn-edit {
  background: linear-gradient(135deg, #7c3aed, #5b21b6) !important;
  color: #fff !important;
  border: none !important;
}

.btn-edit:hover {
  opacity: 0.9;
}

.btn-delete {
  background: #000000 !important;
  color: #ffffff !important;
  border: none !important;
}

.btn-delete:hover {
  background: #1f2933 !important;
}


</style>
</head>

<body>


<nav class="navbar navbar-dark px-4">
  <span class="navbar-brand">
    <i class="bi bi-grid-fill me-2"></i> Admin Panel
  </span>

  <div class="d-flex align-items-center text-white">
    <i class="bi bi-person-circle me-2"></i>
    <?= $_SESSION['username'] ?? 'Admin'; ?>
    <a href="../auth/logout.php" class="btn btn-outline-light btn-sm ms-3">
      Logout
    </a>
  </div>
</nav>


<div class="container py-4">

  
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-0 fw-semibold">Halaman Admin</h4>
      <small class="text-muted">Halaman Kelola Berita</small>
    </div>
    <a href="tambah.php" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> Tambah Berita
    </a>
        <a href="../report/laporan_berita.php" 
            class="btn btn-danger btn-sm mb-3">
        <i class="bi bi-file-earmark-pdf"></i> Download PDF
    </a>
  </div>


  <div class="card">
    <div class="card-body">

      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Tanggal</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>

        <?php if(mysqli_num_rows($result) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $row['id_berita']; ?></td>
            <td class="fw-medium"><?= $row['judul']; ?></td>
            <td>
              <span class="badge badge-category">
                <?= $row['nama_kategori']; ?>
              </span>
            </td>
            <td class="text-muted">
              <?= date('d M Y', strtotime($row['tanggal'])); ?>
            </td>
           <td class="text-end">
              <a href="preview.php?id=<?= $row['id_berita']; ?>" 
                class="btn btn-sm btn-info text-white me-1">
                  <i class="bi bi-eye"></i>
              </a>
              
              <a href="edit.php?id=<?= $row['id_berita']; ?>"
                class="btn btn-sm btn-edit me-1">
                  <i class="bi bi-pencil"></i>
              </a>
              
              <a href="#" 
                class="btn btn-sm btn-delete"
                onclick="confirmDelete(<?= $row['id_berita']; ?>)">
                  <i class="bi bi-trash"></i>
              </a>
      </td>
          </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              <i class="bi bi-file-earmark-text fs-3 d-block mb-2"></i>
              Belum ada berita
            </td>
          </tr>
        <?php endif; ?>

        </tbody>
      </table>

    </div>
  </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Berita?',
        text: 'Berita yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#000000',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
        window.location.href = 'hapus.php?id=' + id;
        }
    });
    }
</script>

</body>
</html>
