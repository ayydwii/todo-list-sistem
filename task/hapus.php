<?php
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];
$id = $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

header("Location: index.php");
