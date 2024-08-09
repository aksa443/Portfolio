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

// Ambil informasi kamera yang disewa
$rentalId = $_GET['rental_id']; // Ganti dengan cara Anda mendapatkan rental_id
$query = "SELECT c.camera_name, c.description, c.rental_price
          FROM cameras c
          INNER JOIN rentals r ON c.id = r.id
          WHERE r.rental_id = '$rentalId'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $cameraName = $row['camera_name'];
    $description = $row['description'];
    $rentalPrice = $row['rental_price'];
} else {
    echo "Camera not found.";
    exit;
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
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Confirmation - Rental Details</h3>
        <h4>Camera Details</h4>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $cameraName; ?></h5>
                <p class="card-text"><?php echo $description; ?></p>
                <p class="card-text">Rental Price: $<?php echo $rentalPrice; ?></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
