<?php
session_start();

if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
}

require_once 'config.php';

$error = '';

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Lakukan validasi input pendaftaran, misalnya memeriksa apakah username atau email sudah digunakan sebelumnya

    // Jika validasi berhasil, lakukan operasi pendaftaran
    // Misalnya, tambahkan data pengguna ke tabel users di database

    // Contoh operasi pendaftaran:
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();

    // Setelah pendaftaran berhasil, berikan role "user" kepada pengguna
    $user_id = $stmt->insert_id;
    $role = "user";
    $stmt = $conn->prepare("INSERT INTO user_roles (user_id, role) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $role);
    $stmt->execute();


    // Setelah pendaftaran berhasil, arahkan pengguna ke halaman login
    header("Location: index.php");
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
    </style>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-5">Register</h2>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Register</button>
        </form>
        <p class="text-danger mt-3"><?php echo $error; ?></p>
        <p class="mt-3">Already have an account? <a href="index.php">Login</a></p>
    </div>
</body>
</html>
