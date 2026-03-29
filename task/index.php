<?php
session_start();
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];
$data = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id='$user_id'");
?>

<div class="container mt-4">
    <h3>Daftar Tugas</h3>

    <a href="tambah.php" class="btn btn-success mb-3">+ Tambah</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Judul</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php while($d = mysqli_fetch_assoc($data)) { ?>
            <tr>
                <td><?= $d['title'] ?></td>
                <td><?= $d['deadline'] ?></td>
                <td>
                    <?php if($d['status'] == 'finished') { ?>
                        <span class="badge bg-success">Selesai</span>
                    <?php } else { ?>
                        <span class="badge bg-warning">Belum</span>
                    <?php } ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="hapus.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger">Hapus</a>
                    <a href="selesai.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-success">✔</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>