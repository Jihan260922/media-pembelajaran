<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$tutor_id = $_COOKIE['admin_id'];

$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_content'])) {
    if (isset($_POST['content_id']) && is_numeric($_POST['content_id'])) {
        $content_id_to_delete = (int)$_POST['content_id'];

        $check_content = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
        $check_content->execute([$content_id_to_delete]);

        if ($check_content->rowCount() > 0) {
            $delete_content = $conn->prepare("DELETE FROM `content` WHERE id = ?");
            $delete_content->execute([$content_id_to_delete]);
            $message[] = 'Konten berhasil dihapus!';
        } else {
            $message[] = 'Konten tidak ditemukan.';
        }
    } else {
        $message[] = 'ID konten tidak valid.';
    }
}

$select_contents = $conn->prepare("SELECT c.*, t.name AS tutor_name FROM `content` c JOIN `tutors` t ON c.tutor_id = t.id");
$select_contents->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Konten</title>
    <link rel="stylesheet" href="../css/admin_style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <?php include '../components/manage_header.php'; ?>

    <section class="manage-content">
        <h1 class="heading">Data Konten Video</h1>

        <div class="table-container" style="max-width: 100%; overflow-x: auto; margin: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <thead>
                    <tr
                        style="background-color:rgb(192, 192, 192); color: #333; text-align: left; border-bottom: 2px solid #ccc;">
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Gambar
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Judul Konten
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Deskripsi
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Status
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Nama Guru
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetch_content = $select_contents->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td data-label="Gambar"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <img src="../uploaded_files/<?= htmlspecialchars($fetch_content['thumb']); ?>"
                                alt="Thumbnail" style="width: 100px; height: auto; border-radius: 5px;">
                        </td>
                        <td data-label="Judul"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_content['title']); ?></td>
                        <td data-label="Deskripsi"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_content['description']); ?></td>
                        <td data-label="Status"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center; font-weight: bold; color: <?= $fetch_content['status'] == 'active' ? 'green' : 'red'; ?>;">
                            <?= htmlspecialchars($fetch_content['status']); ?></td>
                        <td data-label="Nama Tutor"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_content['tutor_name']); ?></td>
                        <td data-label="Aksi"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <form action="" method="post" onsubmit="return confirm('Hapus konten ini?');"
                                style="display:inline;">
                                <input type="hidden" name="content_id" value="<?= $fetch_content['id']; ?>" />
                                <button type="submit" name="delete_content"
                                    style="background: none; border: none; cursor: pointer;">
                                    <i class="fas fa-trash-alt" style="color: red; font-size: 20px;" title="Hapus"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>
</body>

</html>