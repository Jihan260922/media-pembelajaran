<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
    exit; 
}

$select_gallery = $conn->prepare("SELECT * FROM `gallery` ORDER BY date DESC");
$select_gallery->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Kegiatan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
    .gallery {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .gallery .heading {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 2rem;
        text-align: left;
    }

    .box-container {
        margin-top: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .box {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: left;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        grid-template-columns: repeat(2, 1fr);
    }

    .box img,
    .box video {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .box h3 {
        font-size: 2rem;
        color: #333;
        margin: 1rem 0;
        text-align: left;
    }

    .box p {
        font-size: 1.5rem;
        color: #666;
        text-align: left;
    }

    .empty {
        text-align: center;
        font-size: 1.8rem;
        color: #e74c3c;
    }
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="gallery">
        <h1 class="heading">Galeri Kegiatan</h1>
        <div class="box-container">
            <?php
            if ($select_gallery->rowCount() > 0) {
                while ($fetch_gallery = $select_gallery->fetch(PDO::FETCH_ASSOC)) {
                    ?>
            <div class="box">
                <?php if ($fetch_gallery['type'] == 'image'): ?>
                <img src="uploaded_files/<?= htmlspecialchars($fetch_gallery['file']); ?>" alt="">
                <?php elseif ($fetch_gallery['type'] == 'video'): ?>
                <video controls>
                    <source src="uploaded_files/<?= htmlspecialchars($fetch_gallery['file']); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php endif; ?>
                <h3><?= htmlspecialchars($fetch_gallery['description']); ?></h3>
                <p>Tanggal: <?= htmlspecialchars($fetch_gallery['date']); ?></p>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">Belum ada kegiatan yang ditambahkan!</p>';
            }
            ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>