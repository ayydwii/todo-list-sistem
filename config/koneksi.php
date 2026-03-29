<?php
$conn = mysqli_connect("localhost", "root", "", "todo_list");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>