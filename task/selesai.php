<?php
session_start();
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];
$id = $_GET['id'];

$stmt = $pdo->prepare("UPDATE tasks SET status = 'finished' WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: index.php");
?>

