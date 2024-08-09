<?php
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if($_SESSION['role'] !== 'admin') {
    header("Location: user_dashboard.php");
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

// Mendapatkan data kamera dari database
$query = "SELECT camera_id, camera_name, description, rental_price FROM cameras";
$result = $conn->query($query);

// Tambahkan Kamera
if(isset($_POST['add_camera'])) {
    $camera_name = $_POST['camera_name'];
    $description = $_POST['description'];
    $rental_price = $_POST['rental_price'];

    $add_query = "INSERT INTO cameras (camera_name, description, rental_price) VALUES ('$camera_name', '$description', $rental_price)";
    $conn->query($add_query);
}

// Edit Kamera
if(isset($_POST['edit_camera'])) {
    $camera_id = $_POST['camera_id'];
    $camera_name = $_POST['camera_name'];
    $description = $_POST['description'];
    $rental_price = $_POST['rental_price'];

    $edit_query = "UPDATE cameras SET camera_name='$camera_name', description='$description', rental_price=$rental_price WHERE camera_id=$camera_id";
    $conn->query($edit_query);
}

// Hapus Kamera
if(isset($_GET['delete_camera'])) {
    $camera_id = $_GET['delete_camera'];

    $delete_query = "DELETE FROM cameras WHERE camera_id=$camera_id";
    $conn->query($delete_query);
    header("Location: dashboard.php");
    exit();
}

// Mencetak semua kamera ke dalam file PDF
if(isset($_POST['print_all_cameras'])) {
    require('C:/xampp/htdocs/generatePDF/fpdf/fpdf.php');

   $pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Daftar Kamera', 0, 1, 'C');

if ($result->num_rows > 0) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Camera Name', 1);
    $pdf->Cell(120, 10, 'Description', 1);
    $pdf->Cell(30, 10, 'Rental Price', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['camera_name'], 1);
        $pdf->Cell(120, 10, $row['description'], 1);
        $pdf->Cell(30, 10, 'Rp' . $row['rental_price'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Tidak ada kamera yang tersedia.', 0, 1, 'C');
}

$pdf->Output('D', 'daftar_kamera.pdf');
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
            <a href="dashboard.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="container mt-5">
        <h2>Welcome, <?php echo $username; ?>!</h2>
        <h3>Available Cameras</h3>
        <table class="table table-dark table-borderless">
            <thead>
                <tr>
                    <th>Camera Name</th>
                    <th>Description</th>
                    <th>Rental Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['camera_name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>Rp" . $row['rental_price'] . "</td>";
                        echo "<td>
                                <a href='edit_camera.php?camera_id=" . $row['camera_id'] . "' class='btn btn-primary'>Edit</a>
                                <a href='dashboard.php?delete_camera=" . $row['camera_id'] . "' class='btn btn-danger' onclick=\"return confirm('Are you sure you want to delete this camera?');\">Delete</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No cameras available.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Add Camera</h3>
        <form method="POST" action="dashboard.php">
            <div class="mb-3">
                <label for="camera_name" class="form-label">Camera Name</label>
                <input type="text" class="form-control" id="camera_name" name="camera_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="rental_price" class="form-label">Rental Price</label>
                <input type="number" class="form-control" id="rental_price" name="rental_price" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_camera">Add Camera</button>
        </form>

        <form method="POST" action="dashboard.php">
            <button type="submit" class="btn btn-primary" name="print_all_cameras" style="margin-top: 10px; margin-bottom: 10px;">Print All Cameras</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>