<?php
session_start();
if ($_SESSION['user']['role'] != 'karyawan') {
    header("Location: ../auth/login.php");
}
echo "<h2>Dashboard Karyawan</h2>";
?>
<a href="../task/index.php">Kelola Tugas</a>