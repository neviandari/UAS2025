<?php require_once('includes/init.php'); ?>

<?php
$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (isset($_POST['submit'])) :
    if (!$username) {
        $errors[] = 'Username tidak boleh kosong';
    }
    if (!$password) {
        $errors[] = 'Password tidak boleh kosong';
    }

    if (empty($errors)) :
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
        $cek = mysqli_num_rows($query);
        $data = mysqli_fetch_array($query);

        if ($cek > 0) {
            $hashed_password = sha1($password);
            if ($data['password'] === $hashed_password) {
                $_SESSION["user_id"] = $data["id_user"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["role"] = $data["role"];
                redirect_to("dashboard.php");
            } else {
                $errors[] = 'Password salah!';
            }
        } else {
            $errors[] = 'Username atau password salah!';
        }

    endif;

endif;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Sistem Rekomendasi Kuliner Depok</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/vendor/fontawesome-free/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/sb-admin-2.min.css">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(to right, #4e73df, #224abe);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            display: flex;
            background-color: #fff;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            max-width: 1000px;
            width: 100%;
        }
        .login-left, .login-right {
            padding: 2rem;
            width: 50%;
        }
        .login-left {
            background: #f1f5ff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #2a2a2a;
        }
        .login-left h2 {
            font-weight: 800;
            margin-bottom: 1rem;
            color: #4e73df;
        }
        .login-left p {
            font-size: 1rem;
            color: #444;
        }
        .features {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .feature {
            background-color: #fff;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #4e73df;
            font-weight: bold;
        }
        .login-right form .form-control {
            border-radius: 1rem;
            padding: 1rem;
        }
        .btn-login {
            background-color:rgb(78, 151, 223);
            border: none;
            border-radius: 1rem;
            padding: 0.75rem;
            font-weight: 700;
            color: white;
        }
        .btn-login:hover {
            background-color: #3b5ccc;
        }
        .alert {
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-left, .login-right {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-left">
        <h2>Temukan Kuliner Terbaik</h2>
        <p>Jelajahi rekomendasi tempat makan terbaik di Depok dengan sistem pendukung keputusan berbasis SAW.</p>
        <div class="features">
            <div class="feature"><i class="fas fa-check-circle"></i> Rekomendasi Akurat</div>
            <div class="feature"><i class="fas fa-map-marker-alt"></i> Lokasi Strategis</div>
            <div class="feature"><i class="fas fa-star"></i> Rating Terpercaya</div>
            <div class="feature"><i class="fas fa-info-circle"></i> Info Lengkap</div>
        </div>
    </div>
    <div class="login-right">
        <div class="text-center mb-4">
            <img src="assets/img/logo upgris.png" alt="Logo" width="60">
            <h4 class="mt-2">Login Sistem Rekomendasi Kuliner Depok</h4>
        </div>

        <?php if (!empty($errors)) : ?>
            <?php foreach ($errors as $error) : ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" placeholder="Masukkan username Anda" value="<?php echo htmlentities($username); ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" placeholder="Masukkan password Anda" required>
            </div>
            <button type="submit" name="submit" class="btn btn-login btn-block">Masuk</button>
        </form>
        <div class="mt-3 text-center">
            <a href="#">Lupa password?</a> | <a href="signup.php">Belum punya akun? Daftar</a>
        </div>
    </div>
</div>

</body>
</html>
