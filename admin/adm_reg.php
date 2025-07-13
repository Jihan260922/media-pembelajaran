<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $c_pass = filter_var($_POST['c_pass'], FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../uploaded_files/' . $rename;

    $select_email = $conn->prepare("SELECT * FROM `admins` WHERE email = ?");
    $select_email->execute([$email]);

    if ($select_email->rowCount() > 0) {
        $message[] = 'Email sudah terdaftar!';
    } elseif ($pass !== $c_pass) {
        $message[] = 'Password dan konfirmasi tidak cocok!';
    } else {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $insert_admin = $conn->prepare("INSERT INTO `admins` (id, name, email, password, image) VALUES (?, ?, ?, ?, ?)");
            $insert_admin->execute([$id, $name, $email, $hashed_pass, $rename]);

            if ($insert_admin) {
                setcookie('admin_id', $id, time() + 60 * 60 * 24 * 30, '/');
                header('Location: admin_dashboard.php');
                exit;
            } else {
                $message[] = 'Gagal mendaftar, silakan coba lagi.';
            }
        } else {
            $message[] = 'Gagal mengunggah foto profil!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body style="padding-left: 0;">
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message form"><span>' . htmlspecialchars($msg) . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
        }
    }
    ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Daftar Admin</h3>
            <div class="flex">
                <div class="col">
                    <p>Nama<span>*</span></p>
                    <input type="text" name="name" maxlength="50" required placeholder="Masukkan nama" class="box">
                    <p>Email<span>*</span></p>
                    <input type="email" name="email" maxlength="50" required placeholder="Masukkan email" class="box">
                    <p>Foto Profil<span>*</span></p>
                    <input type="file" name="image" class="box" required accept="image/*">
                </div>
                <div class="col">
                    <p>Password<span>*</span></p>
                    <input type="password" name="pass" maxlength="20" required placeholder="Masukkan password"
                        class="box">
                    <p>Konfirmasi Password<span>*</span></p>
                    <input type="password" name="c_pass" maxlength="20" required placeholder="Konfirmasi password"
                        class="box">

                </div>
            </div>
            <p class="link">Sudah punya akun? <a href="adm_log.php">Masuk sekarang</a></p>
            <input type="submit" value="Daftar Sekarang" name="submit" class="btn">
        </form>
    </section>

    <script src="../js/admin_script.js"></script>
</body>

</html>