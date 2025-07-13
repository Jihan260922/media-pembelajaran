<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if (isset($_POST['gallery_id'])) {
    $gallery_id = $_POST['gallery_id'];
    $select_gallery = $conn->prepare("SELECT * FROM `gallery` WHERE id = ?");
    $select_gallery->execute([$gallery_id]);
    $gallery_data = $select_gallery->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['update'])) {
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $file = $_FILES['gallery_file']['name'];
    $file_type = pathinfo($file, PATHINFO_EXTENSION);
    $file = filter_var($file, FILTER_SANITIZE_STRING);
    $rename_file = create_unique_id() . '.' . $file_type;
    $file_tmp_name = $_FILES['gallery_file']['tmp_name'];
    $file_folder = '../uploaded_files/' . $rename_file;

    $update_gallery = $conn->prepare("UPDATE `gallery` SET description = ?, file = ?, type = ? WHERE id = ?");
    $type = ($file_type == 'mp4' || $file_type == 'avi' || $file_type == 'mov') ? 'video' : 'image';
    $update_gallery->execute([$description, $rename_file, $type, $gallery_id]);
    
    move_uploaded_file($file_tmp_name, $file_folder);

    $message[] = 'Galeri berhasil diperbarui!';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galeri</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
    body {
        font-family: 'Nunito', sans-serif;
        background-color: #f4f4f4;
    }

    .form-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }

    .form-container form {
        background-color: #fff;
        border-radius: 0.5rem;
        width: 80rem;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .form-container form h3 {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 1rem;
        color: #333;
    }

    .form-container form .box {
        width: 100%;
        border-radius: 0.5rem;
        margin: 1rem 0;
        padding: 1.4rem;
        font-size: 1.8rem;
        color: #333;
        background-color: #f9f9f9;
    }

    .form-container .message {
        background-color: #e74c3c;
        color: #fff;
        padding: 1rem;
        border-radius: 0.5rem;
        text-align: center;
        margin: 1rem 0;
    }
    </style>
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Edit Galeri Kegiatan</h3>
            <input type="hidden" name="gallery_id" value="<?= $gallery_data['id']; ?>">
            <p>Deskripsi Kegiatan <span>*</span></p>
            <textarea name="description" required class="box" placeholder="Masukkan deskripsi kegiatan" cols="30"
                rows="10"><?= htmlspecialchars($gallery_data['description']); ?></textarea>
            <p>Pilih Gambar/Video <span>*</span></p>
            <input type="file" name="gallery_file" accept="image/*,video/*" class="box">
            <input type="submit" name="update" value="Perbarui Galeri" class="btn">
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