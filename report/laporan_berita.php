<?php
session_start();
require "../config/koneksi.php";
require "../lib/fpdf186/fpdf.php";

if (!isset($_SESSION['login'])) {
    header("Location: ../auth/login.php");
    exit;
}

$sql = "
  SELECT b.judul, k.nama_kategori, b.tanggal, b.views
  FROM berita b
  JOIN kategori k ON b.id_kategori = k.id_kategori
  ORDER BY b.tanggal DESC
";

$result = $con->query($sql);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();


$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'LAPORAN DATA BERITA', 0, 1, 'C');
$pdf->Ln(5);


$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 8, 'No', 1);
$pdf->Cell(70, 8, 'Judul', 1);
$pdf->Cell(35, 8, 'Kategori', 1);
$pdf->Cell(35, 8, 'Tanggal', 1);
$pdf->Cell(20, 8, 'Views', 1);
$pdf->Ln();


$pdf->SetFont('Arial', '', 10);
$no = 1;

while ($row = $result->fetch_assoc()) {

    $pdf->Cell(10, 8, $no++, 1);
    $pdf->Cell(70, 8, substr($row['judul'], 0, 35), 1);
    $pdf->Cell(35, 8, $row['nama_kategori'], 1);
    $pdf->Cell(35, 8, date('d-m-Y', strtotime($row['tanggal'])), 1);
    $pdf->Cell(20, 8, $row['views'], 1);
    $pdf->Ln();
}


$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, 'Dicetak pada: ' . date('d M Y'), 0, 0, 'R');


$pdf->Output('I', 'laporan_berita.pdf');
