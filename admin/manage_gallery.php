<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if (isset($_POST['delete'])) {
    $gallery_id = $_POST['gallery_id'];
    $delete_gallery = $conn->prepare("DELETE FROM `gallery` WHERE id = ?");
    $delete_gallery->execute([$gallery_id]);
    $message[] = 'Galeri berhasil dihapus!';
}

$select_gallery = $conn->prepare("SELECT * FROM `gallery` ORDER BY date DESC");
$select_gallery->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Galeri</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
    .gallery-list {
        margin-top: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .gallery-list .box {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .gallery-list .box img,
    .gallery-list .box video {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .gallery-list .box h3 {
        font-size: 1.8rem;
        color: #333;
        margin: 0.5rem 0;
        text-align: left;
    }

    .gallery-list .box p {
        font-size: 1.5rem;
        color: #666;
        text-align: left;
    }

    .gallery-list .box .actions {
        margin-top: 3rem;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        gap: 2rem;
    }


    .gallery-list .box .actions button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    .gallery-list .box .actions button i {
        margin-right: 0.5rem;
    }

    .gallery-list .box .actions button:hover {
        background-color: #0056b3;
    }

    .gallery-list .box .actions .delete-btn {
        background-color: #e74c3c;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
    }

    .gallery-list .box .actions .delete-btn:hover {
        background-color: #c0392b;
    }
    </style>
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <div style="display: flex; justify-content: flex-end; margin: 20px;">
        <form action="add_gallery.php" method="get">
            <button type="submit" name="submit" class="btn"
                style="background-color: green; color: white; padding: 15px; border: none; cursor: pointer; font-size: 16px; border-radius: 4px;">
                Tambah Dokumentasi Baru
            </button>
        </form>
    </div>

    <section class="gallery-list">

        <?php
        if ($select_gallery->rowCount() > 0) {
            while ($fetch_gallery = $select_gallery->fetch(PDO::FETCH_ASSOC)) {
                ?>
        <div class="box">
            <?php if ($fetch_gallery['type'] == 'image'): ?>
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_gallery['file']); ?>" alt="">
            <?php elseif ($fetch_gallery['type'] == 'video'): ?>
            <video controls>
                <source src="../uploaded_files/<?= htmlspecialchars($fetch_gallery['file']); ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <?php endif; ?>
            <h3><?= htmlspecialchars($fetch_gallery['description']); ?></h3>
            <p>Tanggal: <?= htmlspecialchars($fetch_gallery['date']); ?></p>
            <div class="actions">
                <form action="update_gallery.php" method="post" style="display:inline;">
                    <input type="hidden" name="gallery_id" value="<?= $fetch_gallery['id']; ?>">
                    <button type="submit">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </form>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="gallery_id" value="<?= $fetch_gallery['id']; ?>">
                    <button type="submit" name="delete" class="delete-btn">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Belum ada galeri yang ditambahkan!</p>';
        }
        ?>
    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>