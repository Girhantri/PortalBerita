<?php
session_start();
require "../config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$error = "";

if (isset($_POST['simpan'])) {

    $judul   = trim(htmlspecialchars($_POST['judul']));
    $isi     = trim(htmlspecialchars($_POST['isi']));
    $kategori_input = trim(htmlspecialchars($_POST['kategori']));
    if (!isset($_SESSION['id_user'])) {
        die("SESSION id_user tidak ada. Login ulang.");
    }
    $id_user = (int) $_SESSION['id_user'];
    $tanggal = date('Y-m-d H:i:s');

 
    if ($judul == "") {
        $error = "Judul wajib diisi";
    } elseif (strlen($isi) < 50) {
        $error = "Isi berita minimal 50 karakter";
    } elseif ($kategori_input == "") {
        $error = "Kategori wajib diisi";
    } elseif ($_FILES['gambar']['name'] == "") {
        $error = "Gambar wajib diupload";
    } else {

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

        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $error = "Format gambar harus JPG / PNG";
        } else {

            $namaGambar = time() . "_" . $_FILES['gambar']['name'];
            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                "../assets/img/" . $namaGambar
            );

          $sql = ("
                INSERT INTO berita
                (judul, isi, id_kategori, tanggal, gambar, views, id_user)
                VALUES (?, ?, ?, ?, ?, 0, ?)
            ");


            $stmt = $con->prepare($sql);
            $stmt->bind_param(
                "ssissi",
                $judul,        
                $isi,          
                $id_kategori,  
                $tanggal,      
                $namaGambar,   
                $id_user       
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
<title>Tambah Berita</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background-color: #f4f6f9;
}
.card {
    border-radius: 12px;
}
</style>
</head>
<body>


<nav class="navbar navbar-dark bg-dark shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand fw-semibold">
      <i class="bi bi-speedometer2"></i> Admin Panel
    </span>
    <div class="text-white">
      <?= $_SESSION['username'] ?? 'Admin' ?>
      <a href="../auth/logout.php" class="btn btn-outline-light btn-sm ms-3">
        Logout
      </a>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">
          <i class="bi bi-file-earmark-plus"></i> Tambah Berita
        </div>

        <div class="card-body">

          <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
          <?php endif; ?>

          <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
              <label class="form-label">Judul Berita</label>
              <input type="text" name="judul" class="form-control" placeholder="Masukkan judul berita">
            </div>

            <div class="mb-3">
              <label class="form-label">Isi Berita</label>
              <textarea name="isi" rows="5" class="form-control" placeholder="Minimal 50 karakter"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Kategori</label>
              <input type="text" name="kategori" class="form-control" placeholder="Contoh: Politik">
            </div>

            <div class="mb-3">
              <label class="form-label">Gambar</label>
              <input type="file" name="gambar" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
              <a href="dashboardAdmin.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
              </a>
              <button type="submit" name="simpan" class="btn btn-success">
                <i class="bi bi-save"></i> Simpan Berita
              </button>
            </div>

          </form>

        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
