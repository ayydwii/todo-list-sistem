<?php
session_start();
$_SESSION = array();
session_destroy();
$_SESSION['success_msg'] = "Logout berhasil! Sampai jumpa.";
header("Location: login.php");
exit();
?>

