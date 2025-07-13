<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    header('location:adm_log.php');
    exit;
}

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $c_pass = filter_var($_POST['c_pass'], FILTER_SANITIZE_STRING);
    $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
    $select_profile->execute([$admin_id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
        $update_name->execute([$name, $admin_id]);
        $message[] = 'Nama berhasil di update!';
    }
    if (!empty($email)) {
        $select_email = $conn->prepare("SELECT email FROM `admins` WHERE id != ? AND email = ?");
        $select_email->execute([$admin_id, $email]);
        if ($select_email->rowCount() > 0) {
            $message[] = 'Email sudah terpakai!';
        } else {
            $update_email = $conn->prepare("UPDATE `admins` SET email = ? WHERE id = ?");
            $update_email->execute([$email, $admin_id]);
            $message[] = 'Email berhasil di update!';
        }
    }
    if (!empty($pass) && $pass === $c_pass) {
        $update_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
        $update_pass->execute([password_hash($pass, PASSWORD_DEFAULT), $admin_id]);
        $message[] = 'Password berhasil di update!';
    } elseif (!empty($pass)) {
        $message[] = 'Password dan konfirmasi tidak cocok!';
    }
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id() . '.' . $ext;
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_folder = '../uploaded_files/' . $rename;

        if ($image_size > 8000000) {
            $message[] = 'Ukuran foto profil terlalu besar!';
        } else {
            $update_image = $conn->prepare("UPDATE `admins` SET image = ? WHERE id = ?");
            $update_image->execute([$rename, $admin_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Foto profil berhasil di update!';
        }
    }
}

$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Profil Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
    body {
        background-color: #f4f4f4;
    }

    .form-container {
        max-width: 500px;
        margin: 4rem auto;
        padding: 1rem;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-container h3 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .flex {
        display: flex;
        justify-content: space-between;
        gap: 2rem;
    }

    .col {
        flex: 1;
    }

    .box {
        width: 100%;
        border-radius: 5px;
        padding: 1.4rem;
        font-size: 1.8rem;
        color: #333;
        background-color: #f9f9f9;
        margin: 1rem 0;
    }

    .btn {
        background-color: #708090;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        display: inline-block;
        margin-top: 1rem;
        width: 100%;
    }

    .btn:hover {
        background-color: #aaa;
        color: #333;
    }
    </style>
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Edit Profil Admin</h3>
            <div class="flex">
                <div class="col">
                    <p>Nama Lengkap <span>*</span></p>
                    <input type="text" name="name" maxlength="50"
                        value="<?= htmlspecialchars($fetch_profile['name']); ?>" class="box">
                    <p>Email <span>*</span></p>
                    <input type="email" name="email" maxlength="50"
                        value="<?= htmlspecialchars($fetch_profile['email']); ?>" class="box">
                </div>
                <div class="col">
                    <p>Password <span>*</span></p>
                    <input type="password" name="pass" maxlength="20" placeholder="Masukkan password" class="box">
                    <p>Konfirmasi Password <span>*</span></p>
                    <input type="password" name="c_pass" maxlength="20" placeholder="Konfirmasi password" class="box">
                    <p>Foto Profil <span>*</span></p>
                    <input type="file" name="image" class="box" accept="image/*">
                </div>
            </div>
            <input type="submit" value="Simpan" name="submit" class="btn">
        </form>
    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>