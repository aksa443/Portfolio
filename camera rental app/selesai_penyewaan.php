<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if ($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
}

// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$user = "root"; // Ganti dengan username database Anda
$database = "rental"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $user, '', $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['selesai'])) {
    $rental_id = $_POST['rental_id'];

    // Update status penyewaan
    $update_rental_query = "UPDATE rentals SET status = 'Selesai' WHERE rental_id = $rental_id";
    $conn->query($update_rental_query);

    // Ambil ID kamera yang disewa
    $get_camera_id_query = "SELECT camera_id FROM rentals WHERE rental_id = $rental_id";
    $camera_result = $conn->query($get_camera_id_query);
    $camera_row = $camera_result->fetch_assoc();
    $camera_id = $camera_row['camera_id'];

    // Update status kamera menjadi Tersedia
    $update_camera_query = "UPDATE cameras SET status = 'Tersedia' WHERE camera_id = $camera_id";
    $conn->query($update_camera_query);

    // Redirect ke halaman user_dashboard.php setelah selesai penyewaan
    header("Location: user_dashboard.php");
    exit();
} else {
    // Jika tidak ada data yang dikirim melalui POST, arahkan pengguna kembali ke halaman user_dashboard.php
    header("Location: user_dashboard.php");
    exit();
}
?>
