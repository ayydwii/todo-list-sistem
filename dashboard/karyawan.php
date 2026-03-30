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

// Stats prioritas
$stmt = $pdo->prepare("SELECT priority, COUNT(*) as jml FROM tasks WHERE user_id=? GROUP BY priority");
$stmt->execute([$user_id]);
$prioritas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// On-time stats
$stmt = $pdo->prepare("
    SELECT 
        COUNT(CASE WHEN status='finished' AND deadline > created_at THEN 1 END) as ontime,
        COUNT(*) as total_finished
    FROM tasks WHERE user_id=?
");
$stmt->execute([$user_id]);
$ontime_data = $stmt->fetch(PDO::FETCH_ASSOC);
$ontime_persen = $selesai > 0 ? round(($ontime_data['ontime'] / $selesai) * 100) : 0;

// Per project
$stmt = $pdo->prepare("
    SELECT k.name, COUNT(t.id) as total, 
           SUM(CASE WHEN t.status='finished' THEN 1 ELSE 0 END) as selesai,
           AVG(CASE WHEN t.status='finished' AND t.deadline >= t.created_at THEN 1 ELSE 0 END)*100 as ontime_pct
    FROM tasks t LEFT JOIN kategori k ON t.category_id = k.id 
    WHERE t.user_id=? GROUP BY k.id ORDER BY total DESC LIMIT 5
");
$stmt->execute([$user_id]);
$per_project = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tasks_terbaru = $pdo->prepare("SELECT t.*, k.name as kategori, k.color FROM tasks t LEFT JOIN kategori k ON t.category_id = k.id WHERE t.user_id=? ORDER BY t.priority DESC, t.deadline ASC LIMIT 5");
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
                    <small>Total Task</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-success text-white">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3><?= $selesai ?></h3>
                    <small>Completed</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-warning text-white">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h3><?= $belum ?></h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow h-100">
                <div class="card-body text-center bg-info text-white">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h3><?= $ontime_persen ?>%</h3>
                    <small>On-Time Rate</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h6><i class="fas fa-exclamation-triangle"></i> Prioritas Tinggi</h6>
                </div>
                <div class="card-body">
                    <?php $high = $prioritas['high'] ?? 0; ?>
                    <h2 class="text-danger"><?= $high ?></h2>
                    <small class="text-muted">Task Prioritas Tinggi</small>
                    <?php if($high > 0): ?>
                    <a href="../task/index.php?priority=high" class="btn btn-outline-danger btn-sm mt-2">Kelola</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6><i class="fas fa-balance-scale"></i> Prioritas Medium</h6>
                </div>
                <div class="card-body">
                    <?php $medium = $prioritas['medium'] ?? 0; ?>
                    <h2 class="text-warning"><?= $medium ?></h2>
                    <small class="text-muted">Task Prioritas Medium</small>
                    <?php if($medium > 0): ?>
                    <a href="../task/index.php?priority=medium" class="btn btn-outline-warning btn-sm mt-2">Kelola</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h6><i class="fas fa-arrow-down"></i> Prioritas Rendah</h6>
                </div>
                <div class="card-body">
                    <?php $low = $prioritas['low'] ?? 0; ?>
                    <h2 class="text-success"><?= $low ?></h2>
                    <small class="text-muted">Task Prioritas Rendah</small>
                    <?php if($low > 0): ?>
                    <a href="../task/index.php?priority=low" class="btn btn-outline-success btn-sm mt-2">Kelola</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5><i class="fas fa-project-diagram"></i> Produktivitas per Project</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Total</th>
                                    <th>Selesai</th>
                                    <th>On-Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($per_project as $pp): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pp['name'] ?? 'Umum') ?></td>
                                    <td><?= $pp['total'] ?></td>
                                    <td><?= $pp['selesai'] ?></td>
                                    <td><?= round($pp['ontime_pct']) ?>%</td>
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
                    <h6><i class="fas fa-fire"></i> Task Prioritas</h6>
                </div>
                <div class="card-body">
                    <?php foreach($tasks_terbaru as $t): ?>
                    <div class="d-flex justify-content-between align-items-start mb-3 p-2 border rounded priority-<?= $t['priority'] ?>">
                        <div>
                            <h6 class="mb-1"><?= htmlspecialchars($t['title']) ?></h6>
                            <small style="color:<?= $t['color'] ?>"><?= htmlspecialchars($t['kategori'] ?? 'Umum') ?></small>
                        </div>
                        <span class="badge bg-<?= $t['priority']=='high' ? 'danger' : ($t['priority']=='medium' ? 'warning' : 'success') ?>">
                            <?= ucfirst($t['priority']) ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                    <a href="../task/index.php" class="btn btn-primary btn-sm w-100">Kelola Tugas</a>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</body>
</html>
