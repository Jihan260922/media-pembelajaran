<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if (isset($_POST['announcement_id'])) {
    $announcement_id = $_POST['announcement_id'];
    $select_announcement = $conn->prepare("SELECT * FROM `announcements` WHERE id = ?");
    $select_announcement->execute([$announcement_id]);
    $announcement_data = $select_announcement->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['update'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = '../uploaded_files/' . create_unique_id() . '.' . pathinfo($image, PATHINFO_EXTENSION);
    $update_announcement = $conn->prepare("UPDATE `announcements` SET title = ?, description = ? WHERE id = ?");
    $update_announcement->execute([$title, $description, $announcement_id]);

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Ukuran gambar terlalu besar!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            $update_image = $conn->prepare("UPDATE `announcements` SET image = ? WHERE id = ?");
            $update_image->execute([$image_folder, $announcement_id]);
        }
    }

    $message[] = 'Pengumuman berhasil diperbarui!';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Pengumuman</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Update Pengumuman</h3>
            <input type="hidden" name="announcement_id" value="<?= $announcement_data['id']; ?>">
            <p>Judul Pengumuman <span>*</span></p>
            <input type="text" name="title" required class="box"
                value="<?= htmlspecialchars($announcement_data['title']); ?>">
            <p>Deskripsi Pengumuman <span>*</span></p>
            <textarea name="description" required class="box" cols="30"
                rows="10"><?= htmlspecialchars($announcement_data['description']); ?></textarea>
            <p>Pilih Gambar (Kosongkan jika tidak ingin mengubah gambar)</p>
            <input type="file" name="image" accept="image/*" class="box">
            <input type="submit" name="update" value="Perbarui Pengumuman" class="btn">
        </form>
    </section>

    <?php if (!empty($message)): ?>
    <div class="message">
        <span><?= htmlspecialchars(implode('<br>', $message)); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    <?php endif; ?>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>