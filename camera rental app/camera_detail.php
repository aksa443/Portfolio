<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if($_SESSION['role'] !== 'user') {
    header("Location: dashboard.php");
}

$username = $_SESSION['username'];

// Koneksi ke database
$servername = "localhost"; // Ganti dengan host database Anda
$user = "root"; // Ganti dengan username database Anda
$database = "rental"; // Ganti dengan nama database Anda

$conn = new mysqli($servername, $user, '', $database);
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah parameter id ada
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data kamera dari database berdasarkan id
    $query = "SELECT * FROM cameras WHERE id = $id";
    $result = $conn->query($query);

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $camera_name = $row['camera_name'];
        $description = $row['description'];
        $rental_price = $row['rental_price'];
        $stock = $row['stock'];
    } else {
        // Jika tidak ada data kamera dengan id yang diberikan, arahkan pengguna kembali ke halaman user_dashboard.php
        header("Location: user_dashboard.php");
        exit();
    }
} else {
    // Jika parameter id tidak ada, arahkan pengguna kembali ke halaman user_dashboard.php
    header("Location: user_dashboard.php");
    exit();
}

// Lakukan operasi sewa yang sesuai di sini
// Misalnya, memperbarui status pemesanan atau mengurangi stok kamera
// Sesuaikan dengan logika bisnis dan struktur database Anda
if(isset($_POST['sewa'])) {
    // Contoh operasi sewa:
    // 1. Perbarui status pemesanan menjadi "Sewa"
    $updateQuery = "UPDATE cameras SET status = 'Sewa' WHERE id = $id";
    $conn->query($updateQuery);

    // 2. Kurangi stok kamera
    $newStock = $stock - 1;
    $updateStockQuery = "UPDATE cameras SET stock = $newStock WHERE id = $id";
    $conn->query($updateStockQuery);

    // Setelah proses sewa selesai, arahkan pengguna kembali ke halaman user_dashboard.php
    header("Location: user_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<style>
        body {
            background-image: url('img/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .transparent-table {
            background-color: rgba(255, 255, 255, 0.7);
        }
</style>
<head>
    <title>Camera Detail - Rental Kamera</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">User Dashboard - Rental Kamera</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Camera Detail</h3>
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title"><?php echo $camera_name; ?></h5>
                <p class="card-text"><?php echo $description; ?></p>
                <p class="card-text">Rental Price: $<?php echo $rental_price; ?></p>
                <p class="card-text">Stock: <?php echo $stock; ?></p>
                <form method="post" action="">
                    <button type="submit" name="sewa" class="btn btn-primary">Sewa</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
