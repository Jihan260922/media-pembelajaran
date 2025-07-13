<?php

include '../components/connect.php';

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
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

    $select_tutor_email = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
    $select_tutor_email->execute([$email]);

    if ($select_tutor_email->rowCount() > 0) {
        $message[] = 'Email sudah terdaftar!';
    } elseif ($pass !== $c_pass) {
        $message[] = 'Password dan konfirmasi password tidak cocok!';
    } else {
        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $insert_tutor = $conn->prepare("INSERT INTO `tutors` (id, name, profession, email, password, image) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_tutor->execute([$id, $name, $profession, $email, $hashed_pass, $rename]);

            if ($insert_tutor) {
                $verify_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE email = ?");
                $verify_tutor->execute([$email]);

                if ($verify_tutor->rowCount() > 0) {
                    $row = $verify_tutor->fetch(PDO::FETCH_ASSOC);
                    setcookie('tutor_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
                    header('location:dashboard.php');
                    exit; 
                } else {
                    $message[] = 'Pendaftaran berhasil, tetapi tidak dapat mengarahkan ke dashboard!';
                }
            } else {
                $message[] = 'Gagal mendaftar, silakan coba lagi!';
            }
        } else {
            $message[] = 'Gagal mengunggah gambar!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Guru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body style="padding-left: 0;">

    <?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message form">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Daftar Guru</h3>
            <div class="flex">
                <div class="col">
                    <p>Nama<span>*</span></p>
                    <input type="text" name="name" maxlength="50" required placeholder="Masukan nama" class="box">
                    <p>Kelas <span>*</span></p>
                    <select name="profession" class="box">
                        <option value="" disabled selected>-- Pilih kelas </option>
                        <option value="Wali Kelas A">Wali Kelas A </option>
                        <option value="Guru Kelas A">Guru Kelas A</option>
                        <option value="Wali Kelas B">Wali Kelas B</option>
                        <option value="Guru Kelas B">Guru Kelas B</option>
                        <option value="Materi Tambahan Kelas A">Mater Tambahan Kelas A</option>
                        <option value="Materi Tambahan Kelas B">Materi Tambahan Kelas B</option>
                    </select>
                    <p>Email <span>*</span></p>
                    <input type="email" name="email" maxlength="50" required placeholder="Masukan email" class="box">
                </div>
                <div class="col">
                    <p>Password <span>*</span></p>
                    <input type="password" name="pass" maxlength="20" required placeholder="Masukan password"
                        class="box">
                    <p>Konfirmasi password <span>*</span></p>
                    <input type="password" name="c_pass" maxlength="20" required placeholder="Konfirmasi password "
                        class="box">
                    <p>Foto profil<span>*</span></p>
                    <input type="file" name="image" class="box" required accept="image/*">
                </div>
            </div>
            <p class="link">Sudah mempunyai akun? <a href="login.php">Masuk sekarang</a></p>
            <input type="submit" value="Daftar sekarang" name="submit" class="btn">

        </form>
    </section>

    <script src="../js/admin_script.js"></script>
</body>

</html>