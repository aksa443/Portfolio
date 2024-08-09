<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
    exit();
}

$username = $_SESSION['username'];

// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$user = "root"; // Ganti dengan username database Anda
$database = "rental"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $user, '', $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['rental_id'])) {
    $rental_id = $_POST['rental_id'];

    // Ambil informasi penyewaan berdasarkan rental_id
    $query = "SELECT rentals.rental_id, rentals.rental_date, rentals.return_date, rentals.total_price, cameras.camera_name, cameras.description, cameras.rental_price
          FROM rentals
          JOIN cameras ON rentals.camera_id = cameras.camera_id
          WHERE rentals.rental_id = $rental_id";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rental_id = $row['rental_id'];
        $rental_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime('+7 days'));
        $total_price = $row['total_price'];
        $camera_name = $row['camera_name'];
        $description = $row['description'];
        $rental_price = $row['rental_price'];
    } else {
        // Jika tidak ada data penyewaan dengan rental_id yang diberikan, arahkan pengguna kembali ke halaman user_dashboard.php
        header("Location: user_dashboard.php");
        exit();
    }
} else {
    // Jika parameter rental_id tidak ada, arahkan pengguna kembali ke halaman user_dashboard.php
    header("Location: user_dashboard.php");
    exit();
}

// Mulai menghasilkan file PDF
require('C:/xampp/htdocs/generatePDF/fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();

// Tambahkan konten ke file PDF
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Invoice Penyewaan', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Nama Kamera: ' . $camera_name);
$pdf->Ln();
$pdf->Cell(40, 10, 'Deskripsi: ' . $description);
$pdf->Ln();
$pdf->Cell(40, 10, 'Tanggal Sewa: ' . $rental_date);
$pdf->Ln();
$pdf->Cell(40, 10, 'Tanggal Pengembalian: ' . $return_date);
$pdf->Ln();
$pdf->Cell(40, 10, 'Harga Sewa: Rp' . $rental_price);
$pdf->Ln();
$pdf->Cell(40, 10, 'Total Harga: Rp' . $rental_price);

// Simpan file PDF
$pdf->Output('D', 'detail_penyewaan.pdf');
exit();
?>
