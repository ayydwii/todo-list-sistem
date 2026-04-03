<?php
session_start();
include '../config/koneksi.php';

$role = $_SESSION['user']['role'];
$user_id = $_SESSION['user']['id'];

$kategori = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'] ?: null;
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'] ?? 'medium';

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, category_id, title, description, deadline, priority, status) VALUES (?, ?, ?, ?, ?, ?, 'unfinished')");
    $stmt->execute([$user_id, $category_id, $title, $description, $deadline, $priority]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-plus"></i> Tambah Tugas <?= $role == 'siswa' ? 'Akademik' : 'Kerja' ?></h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Judul Tugas</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?></label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                <?php foreach($kategori as $kat): ?>
                                    <option value="<?= $kat['id'] ?>"><?= $kat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if($role == 'karyawan'): ?>
                        <div class="mb-3">
                            <label class="form-label">Prioritas</label>
                            <select name="priority" class="form-select">
                                <option value="low">Rendah</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">Tinggi</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="datetime-local" name="deadline" class="form-control" required>
                        </div>
                        <button name="submit" class="btn btn-primary">Simpan Tugas</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
