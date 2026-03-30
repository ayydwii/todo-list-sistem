<?php
session_start();
session_start();

$user_id = $_SESSION['user']['id'];
include '../config/koneksi.php';

$kategori = $pdo->query("SELECT * FROM kategori ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Filter
$where = "user_id = ?";
$params = [$user_id];
$filter_category = $_GET['kategori'] ?? '';
$filter_status = $_GET['status'] ?? '';
$filter_priority = $_GET['priority'] ?? '';

if ($filter_category) {
    $where .= " AND category_id = ?";
    $params[] = $filter_category;
}
if ($filter_status) {
    $where .= " AND status = ?";
    $params[] = $filter_status;
}
if ($filter_priority && $role == 'karyawan') {
    $where .= " AND priority = ?";
    $params[] = $filter_priority;
}

$stmt = $pdo->prepare("SELECT t.*, k.name as kategori, k.color FROM tasks t LEFT JOIN kategori k ON t.category_id = k.id WHERE $where ORDER BY deadline ASC");
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daftar Tugas - Todo Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list"></i> Daftar Tugas <?= $role == 'siswa' ? '(Akademik)' : '(Pekerjaan)' ?></h2>
        <a href="tambah.php" class="btn btn-success btn-lg"><i class="fas fa-plus"></i> Tambah Tugas</a>
    </div>

    <!-- Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label"><?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?></label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua <?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?></option>
                        <?php foreach($kategori as $kat): ?>
                            <option value="<?= $kat['id'] ?>" <?= $filter_category == $kat['id'] ? 'selected' : '' ?> style="color:<?= $kat['color'] ?>"><?= $kat['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="unfinished" <?= $filter_status == 'unfinished' ? 'selected' : '' ?>>Belum Selesai</option>
$user_id = $_SESSION['user']['id'];
                    </select>
                </div>
                <?php if($role == 'karyawan'): ?>
                <div class="col-md-2">
$data = mysqli_query($conn, "SELECT * FROM tasks WHERE user_id='$user_id'");
                    <select name="priority" class="form-select">
                        <option value="">Semua Prioritas</option>
                        <option value="low" <?= $filter_priority == 'low' ? 'selected' : '' ?>>Rendah</option>
                        <option value="medium" <?= $filter_priority == 'medium' ? 'selected' : '' ?>>Medium</option>
?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
                <?php if(count($_GET) > 0): ?>
                <div class="col-md-3 d-flex align-items-end">
                    <a href="index.php" class="btn btn-outline-secondary w-100">Hapus Filter</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Stat Ringkas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <i class="fas fa-tasks fa-2x mb-2"></i>
                    <h3><?= count($tasks) ?></h3>
                    <small>Tugas Terkini</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Tugas -->
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Tugas (<?= count($tasks) ?>)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th><?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?></th>
                            <?php if($role == 'karyawan'): ?><th>Prioritas</th><?php endif; ?>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($tasks as $t): ?>

                            <td>
                                <strong><?= htmlspecialchars($t['title']) ?></strong>
                                <?php if($t['priority'] == 'high'): ?>
                                    <span class="badge bg-danger ms-1">!</span>
                                <?php endif; ?>
                            </td>
                            <td><?= strlen($t['description']) > 50 ? substr($t['description'], 0, 50) . '...' : htmlspecialchars($t['description']) ?></td>
                            <td>
                                <?php if($t['kategori']): ?>
                                    <span class="badge" style="background-color:<?= $t['color'] ?>;color:white"><?= htmlspecialchars($t['kategori']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Umum</span>
                                <?php endif; ?>
                            </td>
                            <?php if($role == 'karyawan'): ?>
                            <td><span class="badge bg-<?= $t['priority']=='high' ? 'danger' : ($t['priority']=='medium' ? 'warning' : 'success') ?>"><?= ucfirst($t['priority']) ?></span></td>
                            <?php endif; ?>
                            <td>
                                <span class="badge <?= strtotime($t['deadline']) < time() ? 'bg-danger' : 'bg-info' ?>">
                                    <?= date('d/m H:i', strtotime($t['deadline'])) ?>
                                    <?= strtotime($t['deadline']) < time() ? '<i class="fas fa-exclamation-triangle ms-1"></i>' : '' ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $t['status']=='finished' ? 'bg-success' : 'bg-warning' ?>">
                                    <?= $t['status']=='finished' ? 'Selesai' : 'Belum' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="edit.php?id=<?= $t['id'] ?>" class="btn btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="selesai.php?id=<?= $t['id'] ?>" class="btn btn-outline-success" title="<?= $t['status']=='finished' ? 'Belum Selesai' : 'Selesai' ?>"><i class="fas fa-check"></i></a>
                                    <a href="hapus.php?id=<?= $t['id'] ?>" class="btn btn-outline-danger" title="Hapus" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($tasks)): ?>
                        <tr>
                            <td colspan="<?= $role=='karyawan' ? 7 : 6 ?>" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada tugas</h5>
                                <a href="tambah.php" class="btn btn-primary">+ Tambah Pertama</a>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
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