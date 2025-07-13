<?php
include '../components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $admin_id = $_COOKIE['user_id'];
} else {
    header('location:adm_log.php');
    exit;
}

if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = (int) $_GET['user_id'];

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);

    if ($select_user->rowCount() > 0) {
        $user_data = $select_user->fetch(PDO::FETCH_ASSOC);
    } else {
        header('location:manage_users.php');
        exit;
    }
} else {
    header('location:manage_users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $message = [];

    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
        $update_name->execute([$name, $user_id]);
        $message[] = 'Nama berhasil di update!';
    }
    if (!empty($email)) {
        $select_user_email = $conn->prepare("SELECT email FROM `users` WHERE email = ? AND id != ?");
        $select_user_email->execute([$email, $user_id]);
        if ($select_user_email->rowCount() > 0) {
            $message[] = 'Email sudah terpakai!';
        } else {
            $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $user_id]);
            $message[] = 'Email berhasil di update!';
        }
    }
    if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['c_pass'])) {
        $old_pass = sha1($_POST['old_pass']);
        $new_pass = sha1($_POST['new_pass']);
        $c_pass = sha1($_POST['c_pass']);

        if ($old_pass != $user_data['password']) {
            $message[] = 'Password lama salah!';
        } elseif ($new_pass != $c_pass) {
            $message[] = 'Password baru tidak cocok!';
        } else {
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->execute([$c_pass, $user_id]);
            $message[] = 'Password berhasil diubah!';
        }
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = uniqid() . '.' . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $rename;

        if ($image_size > 8000000) {
            $message[] = 'Ukuran gambar terlalu besar!';
        } else {
            $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $user_id]);
            move_uploaded_file($image_tmp_name, $image_folder);

            if ($user_data['image'] != '' && $user_data['image'] != $rename) {
                unlink('../uploaded_files/' . $user_data['image']);
            }
            $message[] = 'Gambar berhasil diupdate!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Pengguna</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Edit Profil Pengguna</h3>
            <div class="flex">
                <div class="col">
                    <p>Nama</p>
                    <input type="text" name="name" value="<?= htmlspecialchars($user_data['name']); ?>" class="box">
                    <p>Email</p>
                    <input type="email" name="email" value="<?= htmlspecialchars($user_data['email']); ?>" class="box">
                    <p>Password Lama</p>
                    <input type="password" name="old_pass" class="box" placeholder="Masukkan password lama">
                    <p>Password Baru</p>
                    <input type="password" name="new_pass" class="box" placeholder="Masukkan password baru">
                    <p>Konfirmasi Password Baru</p>
                    <input type="password" name="c_pass" class="box" placeholder="Konfirmasi password baru">
                </div>
            </div>

            <p>Upload Gambar Profil</p>
            <input type="file" name="image" class="box" accept="image/*">
            <input type="submit" value="Update Profil" name="submit" class="btn">
        </form>
    </section>
    
    <?php include '../components/footer.php'; ?>
    <script src="../js/admin_script.js"></script>

</body>

</html>