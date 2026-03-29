<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="width:400px;">
            <h4 class="text-center mb-3">Register</h4>

            <form action="proses_register.php" method="POST">
                <input type="text" name="name" class="form-control mb-2" placeholder="Nama" required>
                <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

                <select name="role" class="form-control mb-3">
                    <option value="siswa">Siswa</option>
                    <option value="karyawan">Karyawan</option>
                </select>

                <button class="btn btn-success w-100">Daftar</button>
            </form>
        </div>
    </div>
</body>
</html>