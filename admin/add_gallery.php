<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if (isset($_POST['submit'])) {
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = date('Y-m-d');
    $file = $_FILES['gallery_file']['name'];
    $file_type = pathinfo($file, PATHINFO_EXTENSION);
    $file = filter_var($file, FILTER_SANITIZE_STRING);
    $rename_file = create_unique_id() . '.' . $file_type;
    $file_tmp_name = $_FILES['gallery_file']['tmp_name'];
    $file_folder = '../uploaded_files/' . $rename_file;
    $insert_gallery = $conn->prepare("INSERT INTO `gallery` (description, date, file, type) VALUES (?, ?, ?, ?)");
    $type = ($file_type == 'mp4' || $file_type == 'avi' || $file_type == 'mov') ? 'video' : 'image';
    $insert_gallery->execute([$description, $date, $rename_file, $type]);

    move_uploaded_file($file_tmp_name, $file_folder);

    $message[] = 'Galeri berhasil ditambahkan!';
}

$select_gallery = $conn->prepare("SELECT * FROM `gallery` ORDER BY date DESC");
$select_gallery->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri</title>
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

    .gallery-list {
        margin-top: 2rem;
    }

    .gallery-list .box {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .gallery-list .box img,
    .gallery-list .box video {
        width: 50%;
        height: auto;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .gallery-list .box h3 {
        font-size: 1.8rem;
        color: #333;
        margin: 0.5rem 0;
    }

    .gallery-list .box p {
        font-size: 1.5rem;
        color: #666;
    }

    .gallery-list .box .actions {
        margin-top: 1rem;
    }

    .gallery-list .box .actions button {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 1.5rem;
    }

    .gallery-list .box .actions button:hover {
        background-color: #c0392b;
    }
    </style>
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Tambah Galeri Kegiatan</h3>
            <p>Deskripsi Kegiatan <span>*</span></p>
            <textarea name="description" required class="box" placeholder="Masukkan deskripsi kegiatan" cols="30"
                rows="10"></textarea>
            <p>Pilih Gambar/Video <span>*</span></p>
            <input type="file" name="gallery_file" accept="image/*,video/*" required class="box">
            <input type="submit" name="submit" value="Upload" class="btn">
        </form>
    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>