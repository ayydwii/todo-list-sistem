<?php
session_start();
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM tasks t LEFT JOIN kategori k ON t.category_id = k.id WHERE t.id = ? AND t.user_id = ?");
$stmt->execute([$id, $user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: index.php");
    exit;
}

$role = $_SESSION['user']['role'];
$kategori = $pdo->query("SELECT * FROM kategori ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'] ?: null;
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'] ?? $data['priority'];

    $stmt = $pdo->prepare("UPDATE tasks SET title=?, description=?, category_id=?, deadline=?, priority=? WHERE id=? AND user_id=?");
    $stmt->execute([$title, $description, $category_id, $deadline, $priority, $id, $user_id]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4><i class="fas fa-edit"></i> Edit Tugas</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Judul Tugas</label>
                            <input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?></label>
                            <select name="category_id" class="form-select">
                                <option value="">-- Pilih --</option>
                                <?php foreach($kategori as $kat): ?>
                                    <option value="<?= $kat['id'] ?>" <?= ($data['category_id'] == $kat['id']) ? 'selected' : '' ?>><?= $kat['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if($role == 'karyawan'): ?>
                        <div class="mb-3">
                            <label class="form-label">Prioritas</label>
                            <select name="priority" class="form-select">
                                <option value="low" <?= $data['priority']=='low' ? 'selected' : '' ?>>Rendah</option>
                                <option value="medium" <?= $data['priority']=='medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="high" <?= $data['priority']=='high' ? 'selected' : '' ?>>Tinggi</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="datetime-local" name="deadline" value="<?= date('Y-m-d\TH:i', strtotime($data['deadline'])) ?>" class="form-control" required>
                        </div>
                        <button name="update" class="btn btn-warning">Update Tugas</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
