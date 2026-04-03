<?php
session_start();
include '../config/koneksi.php';
include '../dashboard/layout.php';

// PHP logic here (already have content_index.php test)
$user_id = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];

// Ambil kategori
$kategori = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Filter
$where = "t.user_id = ?";
$params = [$user_id];

$filter_category = $_GET['kategori'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_priority = $_GET['priority'] ?? '';

if ($filter_category) {
    $where .= " AND t.category_id = ?";
    $params[] = $filter_category;
}

if ($filter_status) {
    $where .= " AND t.status = ?";
    $params[] = $filter_status;
}

if ($filter_priority && $role == 'karyawan') {
    $where .= " AND t.priority = ?";
    $params[] = $filter_priority;
}

$stmt = $pdo->prepare("
    SELECT t.*, k.name as kategori, k.color 
    FROM tasks t 
    LEFT JOIN categories k ON t.category_id = k.id 
    WHERE $where 
    ORDER BY t.deadline ASC
");

$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content">

    <div class="d-flex justify-content-between mb-3">
        <h3><i class="fas fa-list"></i> Daftar Tugas</h3>
        <a href="tambah.php" class="btn btn-success"><i class="fas fa-plus"></i> Tambah</a>
    </div>

    <!-- FILTER -->
    <form method="GET" class="row mb-4">
        <div class="col-md-3">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach($kategori as $kat): ?>
                    <option value="<?= $kat['id'] ?>" <?= ($filter_category == $kat['id']) ? 'selected' : '' ?>>
                        <?= $kat['name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="unfinished" <?= ($filter_status=='unfinished')?'selected':'' ?>>Belum Selesai</option>
                <option value="finished" <?= ($filter_status=='finished')?'selected':'' ?>>Selesai</option>
            </select>
        </div>

        <?php if($role == 'karyawan'): ?>
        <div class="col-md-3">
            <select name="priority" class="form-select">
                <option value="">Semua Prioritas</option>
                <option value="low" <?= ($filter_priority=='low')?'selected':'' ?>>Rendah</option>
                <option value="medium" <?= ($filter_priority=='medium')?'selected':'' ?>>Sedang</option>
                <option value="high" <?= ($filter_priority=='high')?'selected':'' ?>>Tinggi</option>
            </select>
        </div>
        <?php endif; ?>

        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <!-- TABEL TUGAS -->
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-tasks me-2"></i>
                Tugas Aktif (<?= count($tasks) ?>)
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if($tasks): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0">Judul</th>
                            <th class="border-0">Kategori</th>
                            <?php if($role=='karyawan'): ?><th class="border-0">Prioritas</th><?php endif; ?>
                            <th class="border-0">Deadline</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tasks as $t): ?>
                        <tr class="task-row priority-<?= $t['priority'] ?? 'low' ?>">
                            <td class="fw-bold"><?= htmlspecialchars($t['title']) ?></td>
                            <td>
                                <?php if($t['kategori']): ?>
                                    <span class="badge fs-6 px-3 py-2" style="background: linear-gradient(45deg, <?= $t['color'] ?>, <?= $t['color'] ?>cc); color: white; font-weight: 600;">
                                        <?= $t['kategori'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary fs-6 px-3 py-2">Umum</span>
                                <?php endif; ?>
                            </td>
                            <?php if($role=='karyawan'): ?>
                            <td>
                                <span class="badge <?= $t['priority']=='high' ? 'bg-danger' : ($t['priority']=='medium' ? 'bg-warning text-dark' : 'bg-success') ?> fs-6 px-3 py-2">
                                    <?= ucfirst($t['priority']) ?>
                                </span>
                            </td>
                            <?php endif; ?>
                            <td>
                                <span class="badge bg-info">
                                    <?= date('d M Y, H:i', strtotime($t['deadline'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if($t['status']=='finished'): ?>
                                    <span class="badge bg-success fs-6 px-3 py-2"><i class="fas fa-check-circle me-1"></i>Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2"><i class="fas fa-clock me-1"></i>Belum</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if($t['status']=='unfinished'): ?>
                                    <a href="selesai.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-success" title="Selesai">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="hapus.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus tugas ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-tasks fa-4x text-muted mb-4"></i>
                <h5 class="text-muted">Belum ada tugas</h5>
                <p class="text-muted">Mulai dengan menambah tugas baru!</p>
                <a href="tambah.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Tambah Tugas Pertama
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
// Highlight approaching deadlines
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    document.querySelectorAll('.task-row').forEach(row => {
        const deadlineStr = row.querySelector('td:nth-child(<?= $role=='karyawan' ? 5 : 4 ?>) span').textContent;
        const deadline = new Date(deadlineStr.replace(/(\d{1,2} \w+ \d{4}, )(\d{1,2}:\d{2})/, '$1' + new Date().getFullYear() + ' $2'));
        if (deadline - now < 24*60*60*1000 && row.querySelector('[data-status="unfinished"]')) {
            row.style.borderLeft = '4px solid #f59e0b';
            row.style.backgroundColor = 'rgba(245, 158, 11, 0.1)';
        }
    });
});
</script>
