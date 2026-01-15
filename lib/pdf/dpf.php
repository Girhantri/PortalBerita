<?php
require('fpdf.php');

// Buat object FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Set font
$pdf->SetFont('Arial', 'B', 16);

// Isi judul
$pdf->Cell(0, 10, 'Hello PDF - FPDF', 0, 1, 'C');

// Ganti font untuk isi
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(5);

$pdf->MultiCell(0, 10, "Ini contoh PDF pertama kamu menggunakan FPDF tanpa composer.\nGampang banget kan!");

// Output PDF ke browser
$pdf->Output();

