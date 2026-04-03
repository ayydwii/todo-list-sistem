<?php
session_start();
include '../config/koneksi.php';
include '../dashboard/layout.php';

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

    echo "<div class='alert alert-success'>Tugas berhasil ditambahkan!</div>";
    // header("Location: index.php");
    // exit;
}
?>

<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2><i class="fas fa-plus-circle text-success me-3"></i>Tambah Tugas <?= $role == 'siswa' ? 'Akademik' : 'Kerja' ?></h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Daftar Tugas</a></li>
                    <li class="breadcrumb-item active">Tambah Tugas</li>
                </ol>
            </nav>
        </div>
        <a href="index.php" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-lg border-0 overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-plus me-2"></i>Buat Tugas Baru
                    </h3>
                </div>
                <div class="card-body p-5">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5 mb-3">Judul Tugas <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-lg" placeholder="Contoh: Matematika Bab 5" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold fs-5 mb-3">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Detail tugas, instruksi khusus..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold fs-5 mb-3"><?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?> <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select form-select-lg">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($kategori as $kat): ?>
                                        <option value="<?= $kat['id'] ?>"><?= $kat['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <?php if($role == 'karyawan'): ?>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold fs-5 mb-3">Prioritas</label>
                                <select name="priority" class="form-select form-select-lg">
                                    <option value="low">🟢 Rendah</option>
                                    <option value="medium" selected>🟡 Sedang</option>
                                    <option value="high">🔴 Tinggi</option>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-5">
                            <label class="form-label fw-bold fs-5 mb-3">Deadline <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </span>
                                <input type="datetime-local" name="deadline" class="form-control form-control-lg" required>
                            </div>
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-md-between">
                            <button name="submit" class="btn btn-success btn-lg px-5 flex-fill">
                                <i class="fas fa-save me-2"></i>Simpan Tugas
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary btn-lg px-5">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
