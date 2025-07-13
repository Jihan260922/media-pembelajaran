<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}
$message = [];
if (isset($_POST['delete'])) {
    $announcement_id = $_POST['announcement_id'];
    $delete_announcement = $conn->prepare("DELETE FROM `announcements` WHERE id = ?");
    $delete_announcement->execute([$announcement_id]);
    $message[] = 'Pengumuman berhasil dihapus!';
}
$select_announcements = $conn->prepare("SELECT id, title, description, image FROM `announcements` ORDER BY id DESC");
$select_announcements->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengumuman</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
    .announcement-list {
        margin-top: 2rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .announcement-list .box {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .announcement-list .box img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;

    }

    .announcement-list .box h3 {
        font-size: 1.8rem;
        color: #333;
        margin: 0.5rem 0;
        text-align: left;
    }

    .announcement-list .box p {
        font-size: 1.5rem;
        color: #666;
        text-align: left;
    }

    .announcement-list .box .actions {
        margin-top: 3rem;
        display: flex;
        justify-content: flex-start;
        gap: 2rem;
    }

    .announcement-list .box .actions button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-size: 1.5rem;
    }

    .announcement-list .box .actions button:hover {
        background-color: #0056b3;
    }

    .announcement-list .box .actions .delete-btn {
        background-color: #e74c3c;
    }

    .announcement-list .box .actions .delete-btn:hover {
        background-color: #c0392b;
    }
    </style>
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <div style="display: flex; justify-content: flex-end; margin: 20px;">
        <form action="add_announcement.php" method="get">
            <button type="submit" name="submit" class="btn"
                style="background-color: green; color: white; padding: 15px; border: none; cursor: pointer; font-size: 16px; border-radius: 4px;">
                Tambah Pengumuman Baru
            </button>
        </form>
    </div>

    <section class="announcement-list">

        <?php
        if ($select_announcements->rowCount() > 0) {
            while ($fetch_announcement = $select_announcements->fetch(PDO::FETCH_ASSOC)) {
                ?>
        <div class="box">
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_announcement['image']); ?>" alt="">
            <h3><?= htmlspecialchars($fetch_announcement['title']); ?></h3>
            <p><?= htmlspecialchars($fetch_announcement['description']); ?></p>
            <div class="actions">
                <form action="update_announcement.php" method="post" style="display:inline;">
                    <input type="hidden" name="announcement_id" value="<?= $fetch_announcement['id']; ?>">
                    <button type="submit">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </form>
                <form action="" method="post" style="display:inline;">
                    <input type="hidden" name="announcement_id" value="<?= $fetch_announcement['id']; ?>">
                    <button type="submit" name="delete" class="delete-btn">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Belum ada pengumuman yang ditambahkan!</p>';
        }
        ?>
    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>