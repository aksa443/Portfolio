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

if (isset($_POST['sewa'])) {
    $camera_id = $_POST['camera_id'];

    // Lakukan operasi sewa yang sesuai di sini
    // Misalnya, memperbarui status pemesanan atau mengurangi stok kamera
    // Sesuaikan dengan logika bisnis dan struktur database Anda

    // Contoh operasi sewa:
    // 1. Perbarui status pemesanan menjadi "Sewa"
    $updateQuery = "UPDATE cameras SET status = 'Sewa' WHERE camera_id = $camera_id";
    $conn->query($updateQuery);

    // 2. Kurangi stok kamera
    $stockQuery = "SELECT stock FROM cameras WHERE camera_id = $camera_id";
    $stockResult = $conn->query($stockQuery);
    $stockRow = $stockResult->fetch_assoc();
    $stock = $stockRow['stock'];

    if ($stock > 0) {
        $newStock = $stock - 1;
        $updateStockQuery = "UPDATE cameras SET stock = $newStock WHERE camera_id = $camera_id";
        $conn->query($updateStockQuery);

        // 3. Simpan informasi penyewaan ke dalam tabel rentals
        $insertQuery = "INSERT INTO rentals (user_id, camera_id) VALUES ('$user_id', '$camera_id')";
        $conn->query($insertQuery);

        // Dapatkan rental_id yang baru saja disimpan
        $rental_id = $conn->insert_id;

        // Setelah proses sewa selesai, arahkan pengguna ke halaman konfirmasi penyewaan dengan menyertakan rental_id
        header("Location: konfirmasi_penyewaan.php?rental_id=$rental_id");
        exit();
    } else {
        // Jika stok kamera habis, tampilkan pesan error
        $error = "Maaf, stok kamera telah habis.";
    }
}

// Ambil data kamera dari database berdasarkan camera_id
if (isset($_GET['camera_id'])) {
    $camera_id = $_GET['camera_id'];
    $query = "SELECT * FROM cameras WHERE camera_id = $camera_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $camera_name = $row['camera_name'];
        $description = $row['description'];
        $rental_price = $row['rental_price'];
    } else {
        // Jika tidak ada data kamera dengan camera_id yang diberikan, arahkan pengguna kembali ke halaman user_dashboard.php
        header("Location: user_dashboard.php");
        exit();
    }
} else {
    // Jika parameter camera_id tidak ada, arahkan pengguna kembali ke halaman user_dashboard.php
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
    <h2>Sewa Kamera</h2>
    <div class="card mx-auto" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title"><?php echo $camera_name; ?></h5>
            <p class="card-text">Rental Price: Rp<?php echo $rental_price; ?></p>
            <p class="card-text">Description: <?php echo $description; ?></p>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <form method="post" action="">
                <input type="hidden" name="camera_id" value="<?php echo $camera_id; ?>">
                <button type="submit" name="sewa" class="btn btn-primary">Sewa</button>
            </form>
        </div>
    </div>
    <div class="mt-3 text-center">
        <a href="user_dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
