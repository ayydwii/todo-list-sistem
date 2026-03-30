<?php
session_start();
include '../config/koneksi.php';

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if ($user['role'] == 'siswa') {
            header("Location: ../dashboard/siswa.php");
        } else {
            header("Location: ../dashboard/karyawan.php");
        }
        exit;
    } else {
        echo "Password salah!";
    }
} else {
    echo "Email tidak ditemukan!";
}
?>