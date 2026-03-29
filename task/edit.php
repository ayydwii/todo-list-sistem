<?php
include '../config/koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tasks WHERE id='$id'"));

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    mysqli_query($conn, "UPDATE tasks SET title='$title' WHERE id='$id'");
    header("Location: index.php");
}
?>

<form method="POST">
    <input type="text" name="title" value="<?= $data['title'] ?>">
    <button name="update">Update</button>
</form>