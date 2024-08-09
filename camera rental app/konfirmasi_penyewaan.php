<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if ($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
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

if (isset($_GET['rental_id'])) {
    $rental_id = $_GET['rental_id'];

    // Ambil informasi penyewaan berdasarkan rental_id
    $query = "SELECT rentals.rental_id, rentals.rental_date, rentals.return_date, rentals.total_price, cameras.camera_name, cameras.description, cameras.rental_price
          FROM rentals
          JOIN cameras ON rentals.camera_id = cameras.camera_id
          WHERE rentals.rental_id = $rental_id";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rental_id = $row['rental_id'];
        $rental_date = new DateTime($rental_date);
        $return_date = $rental_date->add(new DateInterval('P7D'))->format('Y-m-d');
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
    <div class="container mt-5 text-center">
        <h2>Konfirmasi Penyewaan</h2>
        <div class="card mx-auto" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title"><?php echo $camera_name; ?></h5>
                <p class="card-text">Deskripsi: <?php echo $description; ?></p>
                <p class="card-text">Harga Sewa: Rp<?php echo $rental_price; ?></p>
                <p class="card-text">Tanggal Sewa: <span id="rentalDate"></span></p>
                <p class="card-text">Tanggal Pengembalian: <?php echo $return_date; ?></p>
                <p class="card-text">Total Harga: Rp<?php echo $rental_price; ?></p>
                <form method="post" action="selesai_penyewaan.php">
                    <input type="hidden" name="rental_id" value="<?php echo $rental_id; ?>">
                    <button type="submit" name="selesai" class="btn btn-primary">Selesai</button>
                </form>
                <form method="post" action="unduh_detail.php">
                    <input type="hidden" name="rental_id" value="<?php echo $rental_id; ?>">
                    <button type="submit" name="unduh" class="btn btn-success" style="margin-top: 10px; margin-bottom: 10px;">Unduh Invoice</button>
                </form>

            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mendapatkan tanggal saat ini dalam format yyyy-mm-dd
        function getCurrentDate() {
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0');
            var day = String(today.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        // Set nilai tanggal sewa saat halaman dimuat
        document.getElementById("rentalDate").textContent = getCurrentDate();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
