<?php
session_start();
include '../config/koneksi.php';

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user']['id'];

    mysqli_query($conn, "INSERT INTO tasks 
        (title, deadline, user_id, status) 
        VALUES ('$title','$deadline','$user_id','unfinished')");

    header("Location: index.php");
}
?>

<form method="POST">
    Judul: <input type="text" name="title"><br>
    Deadline: <input type="datetime-local" name="deadline"><br>
    <button name="submit">Simpan</button>
</form>