<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if ($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$user = "root"; // Ganti dengan username database Anda
$database = "rental"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $user, '', $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil daftar kamera yang sedang disewa oleh pengguna
$query = "SELECT cameras.camera_id, cameras.camera_name, rentals.rental_id, rentals.status FROM cameras INNER JOIN rentals ON cameras.camera_id = rentals.camera_id WHERE rentals.user_id = $user_id";
$result = $conn->query($query);

if (isset($_POST['pengembalian'])) {
    $rental_id = $_POST['rental_id'];

    // Lakukan operasi pengembalian yang sesuai di sini
    // Misalnya, mengubah status pemesanan menjadi "Kembali" dan menambah stok kamera
    // Sesuaikan dengan logika bisnis dan struktur database Anda

    // Contoh operasi pengembalian:
    // 1. Ubah status pemesanan menjadi "Kembali"
    $updateQuery = "UPDATE rentals SET status = 'Kembali' WHERE rental_id = $rental_id";
    $conn->query($updateQuery);

    // 2. Dapatkan camera_id yang terkait dengan rental_id
    $cameraIdQuery = "SELECT camera_id FROM rentals WHERE rental_id = $rental_id";
    $cameraIdResult = $conn->query($cameraIdQuery);
    $cameraIdRow = $cameraIdResult->fetch_assoc();
    $cameraId = $cameraIdRow['camera_id'];

    // 3. Tambah stok kamera
    $updateStockQuery = "UPDATE cameras SET stock = stock + 1 WHERE camera_id = $cameraId";
    $conn->query($updateStockQuery);

    // Setelah proses pengembalian selesai, arahkan pengguna kembali ke halaman pengembalian
    header("Location: pengembalian.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compitable" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Rental Kamera</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style-nav.css">
</head>
<body>
    
    <header class="header">
        <a href="#" class="logo">CamZone</a>

        <nav class="navbar">
            <a href ="user_dashboard.php">Home</a>
            <a href ="pengembalian.php">Pengembalian</a>
            <a href ="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container mt-5">
        <h2>Pengembalian Kamera</h2>
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-dark table-borderless">
                <thead>
                    <tr>
                        <th scope="col">Kamera</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['camera_name']; ?></td>
                            <td>
                                <?php if ($row['status'] !== 'Kembali') { ?>
                                    <form method="post" action="">
                                        <input type="hidden" name="rental_id" value="<?php echo $row['rental_id']; ?>">
                                        <button type="submit" name="pengembalian" class="btn btn-primary">Kembalikan</button>
                                    </form>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>Tidak ada kamera yang perlu dikembalikan saat ini.</p>
        <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
