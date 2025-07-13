<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if (isset($_POST['submit'])) {
    $announcement_title = filter_var($_POST['announcement_title'], FILTER_SANITIZE_STRING);
    $announcement_description = filter_var($_POST['announcement_description'], FILTER_SANITIZE_STRING);
    $announcement_image = $_FILES['announcement_image']['name'];
    $announcement_image = filter_var($announcement_image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($announcement_image, PATHINFO_EXTENSION);
    $rename_image = create_unique_id() . '.' . $ext;
    $image_tmp_name = $_FILES['announcement_image']['tmp_name'];
    $image_folder = '../uploaded_files/' . $rename_image;
    $message[] = 'Pengumuman berhasil ditambahkan!';
    $insert_announcement = $conn->prepare("INSERT INTO `announcements` (title, description, image) VALUES (?, ?, ?)");
    $insert_announcement->execute([$announcement_title, $announcement_description, $rename_image]);
    move_uploaded_file($image_tmp_name, $image_folder);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengumuman Baru</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Tambah Pengumuman Baru</h3>
            <p>Judul Pengumuman <span>*</span></p>
            <input type="text" name="announcement_title" required class="box" placeholder="Masukkan judul pengumuman">
            <p>Deskripsi Pengumuman <span>*</span></p>
            <textarea name="announcement_description" required class="box" placeholder="Masukkan deskripsi pengumuman"
                cols="30" rows="10"></textarea>
            <p>Pilih Gambar <span>*</span></p>
            <input type="file" name="announcement_image" accept="image/*" required class="box">
            <input type="submit" name="submit" value="Upload" class="btn">
        </form>
    </section>


    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>