<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List Premium - Manajemen Tugas Siswa & Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .feature-card {
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-check-double me-3"></i>
                        Todo List Premium
                    </h1>
                    <p class="lead mb-4">Manajemen tugas cerdas untuk <strong>Siswa</strong> dan <strong>Karyawan</strong>. Atur prioritas, filter mata pelajaran/project, lacak produktivitas dengan mudah!</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="auth/register.php" class="btn btn-light btn-lg px-5">
                            <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                        </a>
                        <a href="auth/login.php" class="btn btn-outline-light btn-lg px-5">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://img.freepik.com/free-vector/gradient-dashboard-interface_23-214902269.jpg?w=740&t=st=1724631419~exp=1724632019~hmac=8b28e2d7f5c5f9d1b6c3e6f4b0d8a5f1e8e6d9c4a2b3c5d7e9f0a1b2c3d4e5f" alt="Dashboard Preview" class="img-fluid rounded shadow-lg" style="max-height: 500px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Siswa -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-primary mb-3">Untuk Siswa <i class="fas fa-graduation-cap"></i></h2>
                <p class="lead">Kelola tugas akademik dengan mudah</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-lg border-0">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-filter fa-3x text-primary mb-3"></i>
                            <h4>Filter Mata Pelajaran</h4>
                            <p class="text-muted">Pisahkan tugas Matematika, Bahasa Inggris, dan lainnya.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-lg border-0">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-pie fa-3x text-success mb-3"></i>
                            <h4>Statistik Belajar</h4>
                            <p class="text-muted">Lihat progres belajar dan persentase pengerjaan per mata pelajaran.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-lg border-0">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-calendar-check fa-3x text-info mb-3"></i>
                            <h4>Jadwal & Deadline</h4>
                            <p class="text-muted">Pengingat otomatis dan highlight deadline mendekati/lewat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Karyawan -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold text-success mb-3">Untuk Karyawan <i class="fas fa-briefcase"></i></h2>
                <p class="lead">Optimalkan produktivitas kerja Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-lg border-0">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h4>Prioritas Tinggi/Medium/Rendah</h4>
                            <p class="text-muted">Atur urgensi task dengan 3 level prioritas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-lg border-0">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-project-diagram fa-3x text-warning mb-3"></i>
                            <h4>Filter per Project</h4>
                            <p class="text-muted">Kelompokkan task berdasarkan project/team.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card h-100 shadow-lg border-0">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-tachometer-alt fa-3x text-dark mb-3"></i>
                            <h4>Statistik Produktivitas</h4>
                            <p class="text-muted">On-time rate, performa per project, progress dashboard.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5 bg-dark text-white">
        <div class="container text-center">
            <h2 class="display-6 mb-4">Siap tingkatkan produktivitas Anda?</h2>
            <p class="lead mb-4">Fitur premium, gratis selamanya. Tanpa iklan, tanpa batas.</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="auth/register.php" class="btn btn-light btn-lg px-5">
                    <i class="fas fa-rocket me-2"></i>Mulai Gratis
                </a>
                <a href="auth/login.php" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-sign-in-alt me-2"></i>Sudah Punya Akun?
                </a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

