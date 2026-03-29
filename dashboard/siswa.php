<?php 
include 'layout.php';
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];

$total = mysqli_num_rows(mysqli_query($conn, 
    "SELECT * FROM tasks WHERE user_id='$user_id'"
));

$selesai = mysqli_num_rows(mysqli_query($conn, 
    "SELECT * FROM tasks WHERE user_id='$user_id' AND status='finished'"
));

$belum = mysqli_num_rows(mysqli_query($conn, 
    "SELECT * FROM tasks WHERE user_id='$user_id' AND status='unfinished'"
));
?>

<div class="row">

    <div class="col-md-4">
        <div class="card shadow p-3">
            <h6>Total Tugas</h6>
            <h2><?= $total ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow p-3">
            <h6>Selesai</h6>
            <h2><?= $selesai ?></h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow p-3">
            <h6>Belum Selesai</h6>
            <h2><?= $belum ?></h2>
        </div>
    </div>

</div>

<!-- TABEL TUGAS TERBARU -->
<div class="card mt-4 shadow p-3">
    <h5>Tugas Terbaru</h5>

    <table class="table mt-3">
        <tr>
            <th>Judul</th>
            <th>Deadline</th>
            <th>Status</th>
        </tr>

        <?php
        $tasks = mysqli_query($conn, 
            "SELECT * FROM tasks WHERE user_id='$user_id' ORDER BY id DESC LIMIT 5"
        );

        while($t = mysqli_fetch_assoc($tasks)) {
        ?>
        <tr>
            <td><?= $t['title'] ?></td>
            <td><?= $t['deadline'] ?></td>
            <td>
                <?php if($t['status']=='finished'){ ?>
                    <span class="badge bg-success">Selesai</span>
                <?php } else { ?>
                    <span class="badge bg-warning">Proses</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</div> <!-- penutup content -->
</body>
</html>