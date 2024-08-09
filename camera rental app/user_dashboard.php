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

// Mendapatkan data kamera dari database
$query = "SELECT camera_id, camera_name, description, rental_price FROM cameras";
$result = $conn->query($query);
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
        <figure class="text-center">
        <blockquote class="blockquote">
            <p class="display-4">Available Camera</p>
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
                        echo "<td>Rp" . $row['rental_price'] . "/ 7 hari</td>";
                        echo "<td><a href='sewa.php?camera_id=" . $row['camera_id'] . "' class='btn btn-primary'>Sewa</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td col-sm-6span='4'>No cameras available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="container mt-5">
    <figure class="text-center">
        <blockquote class="blockquote">
            <p class="display-2">About The Cameras</p>
        </blockquote>
        <figcaption class="blockquote-footer">
            
        </figcaption>
    </figure>
</div>


<div class="row row-col-sm-6s-1 row-col-sm-6s-md-2 g-4">
  <div class="col-sm-6">
    <div class="card mx-auto "style="width: 45rem">
    <img src="img/nikond850.jpg" class="card-img-top" alt="Nikon D850">
      <div class="card-body">
        <h5 class="card-title">Nikon D850</h5>
        <p class="card-text">46MP - Full frame BSI-CMOS Sensor
        No Optical low-pass (anti-aliasing) filter
        ISO 64 - 25600( expands to 32 - 102400)
        3.20" Tilting Screen
        Optical (pentaprism) viewfinder
        7.0fps continuous shooting
        4K at 30fps and FHD at 60fps Video Recording
        Built-in Wireless
        1015g. 146 x 124 x 79 mm
        Weather-sealed Body.</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card mx-auto "style="width: 45rem">
    <img src="img/canoneosr5.jpg" class="card-img-top" alt="Canon EOS R5">
      <div class="card-body">
        <h5 class="card-title">Canon EOS R5</h5>
        <p class="card-text">T45MP - Full frame CMOS Sensor
          ISO 100 - 51200( expands to 50 - 102400)
          5-axis Sensor-shift Image Stabilization
          3.20" Fully Articulated Screen
          5760k dot Electronic viewfinder
          12.0fps (20.0fps Electronic) continuous shooting
          8K at 30fps and 4K at 120fps Video Recording
          10-bit 4:2:2 Color
          Built-in Wireless
          738g. 138 x 98 x 88 mm
          Weather-sealed Body.</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card mx-auto "style="width: 45rem">
    <img src="img/sonya73.jpg" class="card-img-top" alt="Sony A7III">
      <div class="card-body">
        <h5 class="card-title">Sony A7 III</h5>
        <p class="card-text">24MP - Full frame BSI-CMOS Sensor
          ISO 100 - 51200( expands to 50 - 204800)
          5-axis Sensor-shift Image Stabilization
          3.00" Tilting Screen
          2360k dot Electronic viewfinder
          10.0fps continuous shooting
          4k at 30fps and FHD at 120fps Video Recording
          Built-in Wireless
          650g. 127 x 96 x 74 mm
          Weather-sealed Body.</p>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="card mx-auto "style="width: 45rem">
    <img src="img/Fujifilmxt5.jpg" class="card-img-top" alt="Fujifilm XT5">
      <div class="card-body">
        <h5 class="card-title">Fujifilm XT5</h5>
        <p class="card-text">40MP - APS-C BSI-CMOS Sensor
          No Optical low-pass (anti-aliasing) filter
          ISO 125 - 12800( expands to 64-51200)
          5-axis Sensor-shift Image Stabilization
          3.00" Tilting Screen
          3690k dot Electronic viewfinder
          15.0fps (13.0fps Electronic) continuous shooting
          6.2K at 30fps , 4K at 60fps and FHD at 120fps Video Recording
          10-bit 4:2:2 Color
          Built-in Wireless
          557g. 130 x 91 x 64 mm
          Weather-sealed Body.</p>
      </div>
    </div>
  </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
