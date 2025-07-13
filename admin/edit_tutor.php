<?php
include '../components/connect.php';

if (!isset($_GET['id'])) {
    header('location:manage_tutors.php');
    exit;
}

$tutor_id = $_GET['id'];

$select_tutor = $conn->prepare("SELECT * FROM tutors WHERE id = ?");
$select_tutor->execute([$tutor_id]);
$fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);

if (!$fetch_tutor) {
    echo "Tutor tidak ditemukan.";
    exit;
}

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
    $message = [];

    if (!empty($name)) {
        $update = $conn->prepare("UPDATE tutors SET name = ? WHERE id = ?");
        $update->execute([$name, $tutor_id]);
        $message[] = 'Nama berhasil diupdate.';
    }

    if (!empty($email)) {
        $check_email = $conn->prepare("SELECT id FROM tutors WHERE email = ? AND id != ?");
        $check_email->execute([$email, $tutor_id]);
        if ($check_email->rowCount() > 0) {
            $message[] = 'Email sudah digunakan.';
        } else {
            $update = $conn->prepare("UPDATE tutors SET email = ? WHERE id = ?");
            $update->execute([$email, $tutor_id]);
            $message[] = 'Email berhasil diupdate.';
        }
    }

    if (!empty($profession)) {
        $update = $conn->prepare("UPDATE tutors SET profession = ? WHERE id = ?");
        $update->execute([$profession, $tutor_id]);
        $message[] = 'Kelas berhasil diupdate.';
    }

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = uniqid('img_') . '.' . $ext;
    $image_path = '../uploaded_files/' . $rename;

    if (!empty($image)) {
        if ($image_size > 8000000) {
            $message[] = 'Ukuran gambar terlalu besar!';
        } else {
            $update = $conn->prepare("UPDATE tutors SET image = ? WHERE id = ?");
            $update->execute([$rename, $tutor_id]);
            move_uploaded_file($image_tmp, $image_path);

            if (!empty($fetch_tutor['image']) && file_exists('../uploaded_files/' . $fetch_tutor['image'])) {
                unlink('../uploaded_files/' . $fetch_tutor['image']);
            }

            $message[] = 'Foto profil berhasil diupdate.';
        }
    }

    // Ganti password jika diisi
    if (!empty($_POST['new_pass']) && !empty($_POST['c_pass'])) {
        $new_pass = sha1($_POST['new_pass']);
        $c_pass = sha1($_POST['c_pass']);

        if ($new_pass != $c_pass) {
            $message[] = 'Konfirmasi password tidak sesuai.';
        } else {
            $update_pass = $conn->prepare("UPDATE tutors SET password = ? WHERE id = ?");
            $update_pass->execute([$c_pass, $tutor_id]);
            $message[] = 'Password berhasil diupdate.';
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
            <h3>Edit Data Guru</h3>

            <p>Nama Guru</p>
            <input type="text" name="name" class="box" value="<?= htmlspecialchars($fetch_tutor['name']); ?>">

            <p>Email</p>
            <input type="email" name="email" class="box" value="<?= htmlspecialchars($fetch_tutor['email']); ?>">

            <p>Kelas</p>
            <select name="profession" class="box">
                <option value="<?= $fetch_tutor['profession']; ?>" selected><?= $fetch_tutor['profession']; ?></option>
                <option value="" disabled selected>-- Pilih kelas </option>
                <option value="Wali Kelas A">Wali Kelas A </option>
                <option value="Guru Kelas A">Guru Kelas A</option>
                <option value="Wali Kelas B">Wali Kelas B</option>
                <option value="Guru Kelas B">Guru Kelas B</option>
                <option value="Materi Tambahan Kelas A">Mater Tambahan Kelas A</option>
                <option value="Materi Tambahan Kelas B">Materi Tambahan Kelas B</option>
            </select>

            <p>Foto Profil</p>
            <input type="file" name="image" accept="image/*" class="box">

            <p>Password Baru</p>
            <input type="password" name="new_pass" class="box" placeholder="Kosongkan jika tidak diganti">

            <p>Konfirmasi Password</p>
            <input type="password" name="c_pass" class="box" placeholder="Konfirmasi password baru">

            <input type="submit" name="submit" value="Update Guru" class="btn">

        </form>
    </section>

    <?php include '../components/footer.php'; ?>
    <script src="../js/admin_script.js"></script>
</body>

</html>