<?php
require_once __DIR__ . '/../config/koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $nama = $_POST['username'];
    $pw = $_POST['password'];

    $hashPw = password_hash($pw, PASSWORD_DEFAULT);
    $query = "INSERT INTO users(username,password) VALUES ('$nama','$hashPw')";

    $result = mysqli_query($con,$query);

    if($result){
        header("Location: login.php");
        exit;
    }else{
        echo "Gagal Menyimpan data";
    }
}else{
    echo "Data Belum Dikirim";
}

?>