<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: ../dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login | Penjualan Elektronik</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- AdminLTE & FontAwesome -->
  <link rel="stylesheet" href="../assets/adminlte3\adminlte3/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../assets/adminlte3\adminlte3/plugins/fontawesome-free/css/all.min.css">

  <style>
    body{background:linear-gradient(135deg,#0d6efd,#6610f2);min-height:100vh;display:flex;align-items:center;justify-content:center}
    .login-card{border-radius:15px;overflow:hidden}
  </style>
</head>

<body>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow login-card">

        <div class="card-header text-center bg-primary text-white">
          <h4><i class="fas fa-bolt"></i> Elektronik Store</h4>
        </div>

        <div class="card-body">

          <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
              <i class="fas fa-exclamation-circle"></i>
              Email atau password salah
            </div>
          <?php endif; ?>

          <form action="login_process.php" method="POST">
            <div class="mb-3">
              <label>Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" required>
              </div>
            </div>

            <div class="mb-3">
              <label>Password</label>
              <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control" required>
              </div>
            </div>

            <button class="btn btn-primary w-100">
              <i class="fas fa-sign-in-alt"></i> Login
            </button>
          </form>

        </div>

      </div>
    </div>
  </div>
</div>

</body>
</html>