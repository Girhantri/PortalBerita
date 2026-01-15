<?php
require_once __DIR__ . '/../config/koneksi.php';

$username = "admin";
$password = "admin";

$hash = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO users (username, password, role)
          VALUES ('$username', '$hash', 'admin')";

mysqli_query($con, $query);

echo "Admin berhasil ditambahkan";
