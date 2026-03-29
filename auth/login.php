<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
<?php 
if (isset($_SESSION['success_msg'])) {
    echo '<div class="alert alert-success alert-dismissible fade show position-fixed" style="top:20px; right:20px; z-index:9999;" role="alert">
            ' . $_SESSION['success_msg'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['success_msg']);
}
if (isset($_SESSION['error_msg'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show position-fixed" style="top:20px; right:20px; z-index:9999;" role="alert">
            ' . $_SESSION['error_msg'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['error_msg']);
}
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width:350px;">
        <h4 class="text-center mb-3">Login</h4>

        <form action="proses_login.php" method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100">Login</button>
        </form>

        <p class="text-center mt-3">
            Belum punya akun? <a href="register.php">Daftar</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
