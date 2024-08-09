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

    // Setelah pendaftaran berhasil, arahkan pengguna ke halaman login
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Login Page</title>
</head>

<body>

    <div class="login-card-container">
        <div class="login-card">
            <div class="login-card-logo">
                <img src="logo.png" alt="logo">
            </div>
            <div class="login-card-header">
                <h1>Create Account</h1>
                <div>Please login to use the platform</div>
            </div>
            <form class="login-card-form" method="POST" action="">
                <div class="form-item">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                </div>
                <div class="form-item">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="form-item">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <button type="submit" name="submit">Create Account</button>
            </form>
            <div class="login-card-footer">
                Already have an account? <a href="index.php">back to dashboard</a>
            </div>
        </div>
            </div>
        </div>
    </div>

</body>

</html>
