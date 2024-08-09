<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if ($_SESSION['role'] !== 'admin') {
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

if (isset($_GET['camera_id'])) {
    $camera_id = $_GET['camera_id'];

    // Mendapatkan data kamera berdasarkan id
    $query = "SELECT * FROM cameras WHERE camera_id = $camera_id";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $camera_name = $row['camera_name'];
        $description = $row['description'];
        $rental_price = $row['rental_price'];
    } else {
        // Redirect jika id tidak valid
        header("Location: dashboard.php");
    }
} else {
    // Redirect jika id tidak diberikan
    header("Location: dashboard.php");
}

// Update data kamera
if (isset($_POST['update_camera'])) {
    $camera_name = $_POST['camera_name'];
    $description = $_POST['description'];
    $rental_price = $_POST['rental_price'];

    // Query update data kamera
    $query = "UPDATE cameras SET camera_name = '$camera_name', description = '$description', rental_price = '$rental_price' WHERE camera_id = $camera_id";
    if ($conn->query($query) === TRUE) {
        // Redirect setelah berhasil mengupdate data
        header("Location: dashboard.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
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
            <a href ="dashboard.php">Home</a>
            <a href ="logout.php">Logout</a>
        </nav>
    </header>
    <div class="container mt-5">
        <h2>Edit Camera</h2>
        <form method="POST" action="edit_camera.php?camera_id=<?php echo $camera_id; ?>">
            <div class="mb-3">
                <label for="camera_name" class="form-label">Camera Name</label>
                <input type="text" class="form-control" camera_id="camera_name" name="camera_name" value="<?php echo $camera_name; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" camera_id="description" name="description" rows="3" required><?php echo $description; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="rental_price" class="form-label">Rental Price</label>
                <input type="number" class="form-control" camera_id="rental_price" name="rental_price" value="<?php echo $rental_price; ?>" required>
            </div>
            <button type="submit" name="update_camera" class="btn btn-primary">Update</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
