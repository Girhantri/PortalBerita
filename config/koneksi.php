<?php

$host = "localhost";
$username = "root";
$pw = "root";
$dbname = "responsi";

$con = mysqli_connect($host,$username,$pw,$dbname);

if(!$con){
    echo "Tidak Konek";
}
?>