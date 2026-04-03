<?php
// Content for task/index.php - original content wrapped

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

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Tugas</h3>
        <a href="tambah.php" class="btn btn-success">+ Tambah</a>
    </div>

    <!-- FILTER -->
    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach($kategori as $kat): ?>
                    <option value="<?= $kat['id'] ?>" <?= $filter_category == $kat['id'] ? 'selected' : '' ?>>
                        <?= $kat['name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="unfinished" <?= $filter_status=='unfinished'?'selected':'' ?>>Belum</option>
                <option value="finished" <?= $filter_status=='finished'?'selected':'' ?>>Selesai</option>
            </select>
        </div>

        <?php if($role == 'karyawan'): ?>
        <div class="col-md-3">
            <select name="priority" class="form-select">
                <option value="">Semua Prioritas</option>
                <option value="low" <?= $filter_priority=='low'?'selected':'' ?>>Low</option>
                <option value="medium" <?= $filter_priority=='medium'?'selected':'' ?>>Medium</option>
                <option value="high" <?= $filter_priority=='high'?'selected':'' ?>>High</option>
            </select>
        </div>
        <?php endif; ?>

        <div class="col-md-3">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <!-- TABEL -->
    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <?php if($role=='karyawan'): ?><th>Prioritas</th><?php endif; ?>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php if($tasks): ?>
                    <?php foreach($tasks as $t): ?>
                    <tr class="priority-<?= $t['priority'] ?>">
                        <td><?= htmlspecialchars($t['title']) ?></td>

                        <td>
                            <?= $t['kategori'] 
                                ? "<span style='color:$t[color]'>$t[kategori]</span>" 
                                : 'Umum' ?>
                        </td>

                        <?php if($role=='karyawan'): ?>
                        <td><?= ucfirst($t['priority']) ?></td>
                        <?php endif; ?>

                        <td><span class="badge bg-info"><?= date('d/m/Y H:i', strtotime($t['deadline'])) ?></span></td>

                        <td>
                            <?= $t['status']=='finished' 
                                ? '<span class="badge bg-success">Selesai</span>' 
                                : '<span class="badge bg-warning text-dark">Belum</span>' ?>
                        </td>

                        <td>
                            <a href="edit.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <?php if($t['status']=='unfinished'): ?>
                            <a href="selesai.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-success"><i class="fas fa-check"></i></a>
                            <?php endif; ?>
                            <a href="hapus.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $role=='karyawan' ? '6' : '5' ?>" class="text-center py-4">
                            <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada tugas</p>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
