<?php 
session_start();

// 🔒 Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar p-3">
    <h4 class="text-center mb-4">Todo Premium</h4>

    <a href="#"><i class="fas fa-home"></i> Dashboard</a>
    <a href="../task/index.php"><i class="fas fa-list"></i> Tugas</a>
    <a href="#"><i class="fas fa-chart-bar"></i> Statistik</a>
    <a href="#"><i class="fas fa-bell"></i> Reminder</a>
    <a href="../auth/logout.php" class="text-danger">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a>
</div>

<!-- CONTENT -->
<div class="content">

<!-- NAVBAR -->
<nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded">
    <div class="container-fluid">
        <span>
            Halo, <?= $_SESSION['user']['name'] ?? 'User'; ?> 👋
        </span>
    </div>
</nav>