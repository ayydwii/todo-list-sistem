<?php
session_start();
include '../config/koneksi.php';

$user_id = $_SESSION['user']['id'];
$role = $_SESSION['user']['role'];

if (isset($_POST['tambah'])) {
    $name = $_POST['name'];
    $color = $_POST['color'] ?? '#007bff';
    $stmt = $pdo->prepare("INSERT INTO kategori (name, color) VALUES (?, ?)");
    $stmt->execute([$name, $color]);
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $color = $_POST['color'];
    $stmt = $pdo->prepare("UPDATE kategori SET name=?, color=? WHERE id=?");
    $stmt->execute([$name, $color, $id]);
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM kategori WHERE id=?");
    $stmt->execute([$id]);
}

$kategori = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4"><i class="fas fa-tags"></i> Kelola <?= $role == 'siswa' ? 'Mata Pelajaran' : 'Project' ?></h2>
    
    <!-- Form Tambah -->
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            <h5><i class="fas fa-plus"></i> Tambah Baru</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Warna</label>
                    <input type="color" name="color" class="form-control form-control-color" value="#007bff">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button name="tambah" class="btn btn-success w-100">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Kategori -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-list"></i> Daftar Kategori (<?= count($kategori) ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Warna</th>
                            <th>Tanggal Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($kategori as $k): ?>
                        <tr>
                            <td><?= htmlspecialchars($k['name']) ?></td>
                            <td><span class="badge p-2" style="background-color:<?= $k['color'] ?>"><?= $k['color'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($k['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary edit-kat" data-id="<?= $k['id'] ?>" data-name="<?= htmlspecialchars($k['name']) ?>" data-color="<?= $k['color'] ?>" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="?hapus=<?= $k['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus? Data tugas terkait akan tetap ada.')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($kategori)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Belum ada kategori</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Tugas</a>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Kategori</h5>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warna</label>
                        <input type="color" name="color" id="edit_color" class="form-control form-control-color">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button name="edit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.edit-kat').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_name').value = this.dataset.name;
        document.getElementById('edit_color').value = this.dataset.color;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>
</body>
</html>

