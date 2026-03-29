<?php
session_start();
include '../config/koneksi.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

try {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role]);
    $_SESSION['success_msg'] = "Registrasi berhasil! Silakan login dengan akun baru Anda.";
} catch (PDOException $e) {
    $_SESSION['error_msg'] = "Email sudah terdaftar! Gunakan email lain.";
}

header("Location: login.php");
exit();
?>

