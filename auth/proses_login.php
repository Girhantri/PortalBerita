<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$query_login = $con->prepare("SELECT * FROM users WHERE username = ?");
$query_login->bind_param("s", $username);
$query_login->execute();
$result = $query_login->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        
        $_SESSION['login']    = true;
        $_SESSION['id_user']  = $user['id']; 
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboardAdmin.php");
        } else {
            header("Location: ../user/dashboardUser.php");
        }
        exit;

    } else {
        $_SESSION['error'] = "Password salah";
        header("Location: ../auth/login.php");
        exit;
    }

} else {
    $_SESSION['error'] = "Username tidak ditemukan";
    header("Location: ../auth/login.php");
    exit;
}
?>