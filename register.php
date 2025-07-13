<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $id = create_unique_id();
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $class = filter_var($_POST['class'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = create_unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/' . $rename;

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select_user->execute([$email]);

    if ($select_user->rowCount() > 0) {
        $message[] = 'Email sudah terdaftar!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Konfirmasi password tidak cocok!';
        } else {
            $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
            $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, class, password, image) VALUES(?,?,?,?,?,?)");
            $insert_user->execute([$id, $name, $email, $class, $hashed_pass, $rename]);
            move_uploaded_file($image_tmp_name, $image_folder);

            $verify_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
            $verify_user->execute([$email]);
            $row = $verify_user->fetch(PDO::FETCH_ASSOC);

            if ($verify_user->rowCount() > 0) {
                setcookie('user_id', $row['id'], time() + 60 * 60 * 24 * 30, '/');
                header('location:home.php');
                exit;
            }
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
    <title>Daftar</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <section class="form-container">
        <form class="register" action="" method="post" enctype="multipart/form-data">
            <h3>Daftar Siswa</h3>
            <div class="flex">
                <div class="col">
                    <p>Nama<span>*</span></p>
                    <input type="text" name="name" placeholder="Masukan nama" maxlength="50" required class="box">
                    <p>Email<span>*</span></p>
                    <input type="email" name="email" placeholder="Masukan email" maxlength="50" required class="box">
                    <p>Kelas<span>*</span></p>
                    <select name="class" class="box" required>
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        <option value="A">Kelas A</option>
                        <option value="B">Kelas B</option>
                    </select>
                </div>
                <div class="col">
                    <p>Password <span>*</span></p>
                    <input type="password" name="pass" placeholder="Masukan password" maxlength="20" required
                        class="box">
                    <p>Konfirmasi password <span>*</span></p>
                    <input type="password" name="cpass" placeholder="Konfirmasi password" maxlength="20" required
                        class="box">
                </div>
            </div>
            <p>Foto profil<span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">
            <p class="link">Sudah mempunyai akun? <a href="login.php">Masuk</a></p>
            <input type="submit" name="submit" value="Daftar sekarang" class="btn">
        </form>
    </section>

    <script src="js/script.js"></script>

</body>

</html>