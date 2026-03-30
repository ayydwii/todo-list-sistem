<?php 
include 'layout.php';
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=?");
$stmt->execute([$user_id]);
$total = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=? AND status='finished'");
$stmt->execute([$user_id]);
$selesai = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE user_id=? AND status='unfinished'");
$stmt->execute([$user_id]);
$belum = $stmt->fetchColumn();

$persen = $total > 0 ? round(($selesai / $total) * 100) : 0;

$stmt = $pdo->prepare("
    SELECT k.name, COUNT(t.id) as total, 
           SUM(CASE WHEN t.status='finished' THEN 1 ELSE 0 END) as selesai
    FROM tasks t LEFT JOIN kategori k ON t.category_id = k.id 
    WHERE t.user_id=? GROUP BY k.id ORDER BY total DESC LIMIT 5
");
$stmt->execute([$user_id]);
$per_kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tasks_terbaru = $pdo->prepare("SELECT t.*, k.name as kategori FROM tasks t LEFT JOIN kategori k ON t.category_id = k.id WHERE t.user_id=? ORDER BY t.created_at DESC LIMIT 5");
$tasks_terbaru->execute([$user_id]);
$tasks_terbaru = $tasks_terbaru->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-primary text-white">
                    <i class="fas fa-tasks fa-2x mb-2"></i>
                    <h3><?= $total ?></h3>
                    <small>Total Tugas</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-success text-white">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3><?= $selesai ?></h3>
                    <small>Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-warning text-white">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h3><?= $belum ?></h3>
                    <small>Belum Selesai</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-info text-white">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h3><?= $persen ?>%</h3>
                    <small>Progres Belajar</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5><i class="fas fa-book"></i> Performa per Mata Pelajaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Total</th>
                                    <th>Selesai</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($per_kategori as $pk): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pk['name'] ?? 'Umum') ?></td>
                                    <td><?= $pk['total'] ?></td>
                                    <td><?= $pk['selesai'] ?></td>
                                    <td>
                                        <span class="badge <?= $pk['total'] > 0 ? 'bg-' . ($pk['selesai']/$pk['total'] > 0.7 ? 'success' : 'warning') : 'secondary' ?>">
                                            <?= $pk['total'] > 0 ? round(($pk['selesai']/$pk['total'])*100) : 0 ?>%
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6><i class="fas fa-list"></i> Tugas Terbaru</h6>
                </div>
                <div class="card-body">
                    <?php foreach($tasks_terbaru as $t): ?>
                    <div class="d-flex justify-content-between align-items-start mb-3 p-2 border rounded">
                        <div>
                            <h6 class="mb-1"><?= htmlspecialchars($t['title']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($t['kategori'] ?? 'Umum') ?></small>
                        </div>
                        <span class="badge <?= $t['status']=='finished' ? 'bg-success' : 'bg-warning' ?>">
                            <?= $t['status']=='finished' ? 'Selesai' : 'Proses' ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                    <a href="../task/index.php" class="btn btn-primary btn-sm w-100">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
</div>

</div> <!-- content -->
</body>
</html>
