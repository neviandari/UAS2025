<?php
require_once('includes/init.php');

$errors = [];
$sukses = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    $role = 2; // default user biasa

    // Validasi
    if (!$nama) $errors[] = 'Nama tidak boleh kosong';
    if (!$username) $errors[] = 'Username tidak boleh kosong';
    if (!$email) $errors[] = 'Email tidak boleh kosong';
    if (!$password) $errors[] = 'Password tidak boleh kosong';
    if ($password !== $konfirmasi_password) $errors[] = 'Konfirmasi password tidak sesuai';

    // Cek apakah username sudah ada
    if ($username) {
        $cek = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
        if (mysqli_num_rows($cek) > 0) {
            $errors[] = 'Username sudah digunakan';
        }
    }

    // Simpan jika tidak ada error
    if (empty($errors)) {
        $pass_hash = sha1($password);
        $simpan = mysqli_query($koneksi, "INSERT INTO user (username, password, nama, email, role) VALUES ('$username', '$pass_hash', '$nama', '$email', $role)");
        if ($simpan) {
            $sukses = true;
            header("Location: login.php?register=success");
            exit;
        } else {
            $errors[] = 'Gagal menyimpan data';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Akun - Kuliner Depok</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/vendor/fontawesome-free/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background-color: #f4f6f8;
    }

    .container {
      display: flex;
      min-height: 100vh;
    }

    .left {
      flex: 1;
      background: linear-gradient(to bottom right, #4e73df, #224abe);
      padding: 4rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #1f2937;
    }

    .left .logo {
      display: flex;
      align-items: center;
      margin-bottom: 2rem;
    }

    .left .logo i {
      background-color: #1f2937;
      color: white;
      padding: 0.5rem;
      border-radius: 8px;
      margin-right: 0.75rem;
    }

    .left h1 {
      font-size: 1.8rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
    }

    .left p {
      font-size: 0.95rem;
      margin-bottom: 2rem;
      color:rgb(216, 224, 237);
    }

    form {
      background-color: white;
      border-radius: 1rem;
      padding: 2rem;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    form .form-group {
      margin-bottom: 1.25rem;
    }

    form label {
      display: block;
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 0.75rem;
      outline: none;
      font-size: 0.95rem;
    }

    form input[type="checkbox"] {
      margin-right: 0.5rem;
    }

    form .form-check {
      font-size: 0.85rem;
      color: #4b5563;
      margin-bottom: 1.5rem;
    }

    form button {
      background-color: #4e73df;
      color: white;
      border: none;
      padding: 0.75rem;
      width: 100%;
      font-weight: 600;
      border-radius: 0.75rem;
      cursor: pointer;
      transition: background 0.3s;
    }

    form button:hover {
      background-color: #16a34a;
    }

    form .text-center {
      margin-top: 1rem;
      font-size: 0.9rem;
    }

    form .text-center a {
      color: #4e73df;
      text-decoration: none;
      font-weight: 600;
    }

    .right {
      flex: 1;
      padding: 4rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      background-color:rgb(97, 133, 240);
    }

    .right .card {
      background-color: white;
      border-radius: 1rem;
      padding: 2rem;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    }

    .right .card i {
      font-size: 2.5rem;
      color: #374151;
      margin-bottom: 1rem;
    }

    .right .card h2 {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .right .card p {
      font-size: 0.9rem;
      color: #4b5563;
      margin-bottom: 2rem;
    }

    .features {
      display: grid;
      gap: 1rem;
      margin-top: 1rem;
    }

    .feature {
      background-color: #fff;
      padding: 1rem;
      border-radius: 0.75rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .feature i {
      font-size: 1.25rem;
    }

    .green { color: #22c55e; }
    .blue { color: #3b82f6; }
    .yellow { color: #facc15; }
    .pink { color: #ef4444; }
  </style>
</head>
<body>

<div class="container">
  <div class="left">
    <div class="logo">
      <img src="assets/img/logo upgris.png" alt="Logo" width="60">
      <div>
        <strong>Kuliner Depok</strong><br>
        <small>Sistem Rekomendasi</small>
      </div>
    </div>
    <h1>Daftar Akun Baru</h1>
    <p>Bergabunglah untuk mendapatkan rekomendasi kuliner terbaik di Depok</p>

    <form action="signup.php" method="POST">
      <div class="form-group">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan nama lengkap Anda" required>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" placeholder="Masukkan username Anda" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email Anda" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Minimal 8 karakter" required>
      </div>
      <div class="form-group">
        <label>Konfirmasi Password</label>
        <input type="password" name="konfirmasi_password" placeholder="Ulangi password Anda" required>
      </div>
      <button type="submit">Daftar Sekarang</button>
      <div class="text-center">
        Sudah punya akun? <a href="login.php">Masuk di sini</a>
      </div>
    </form>
  </div>

  <div class="right">
    <div class="card">
      <i class="fas fa-shopping-cart"></i>
      <h2>Mulai Petualangan Kuliner</h2>
      <p>Bergabunglah dengan ribuan pengguna lain untuk menemukan pengalaman kuliner terbaik di Depok</p>
      <div class="features">
        <div class="feature green"><i class="fas fa-user-check"></i> Rekomendasi Personal</div>
        <div class="feature blue"><i class="fas fa-map-marker-alt"></i> Tracking Kunjungan</div>
        <div class="feature yellow"><i class="fas fa-star"></i> Review & Rating</div>
        <div class="feature pink"><i class="fas fa-bell"></i> Notifikasi Promo</div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
