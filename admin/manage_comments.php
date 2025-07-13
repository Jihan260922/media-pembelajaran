<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$tutor_id = $_COOKIE['admin_id'];
$message = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    if (isset($_POST['comment_id']) && is_numeric($_POST['comment_id'])) {
        $comment_id_to_delete = (int)$_POST['comment_id'];
        $check_comment = $conn->prepare("SELECT c.id FROM `comments` c WHERE c.id = ?");
        $check_comment->execute([$comment_id_to_delete]);
        if ($check_comment->rowCount() > 0) {
            $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
            $delete_comment->execute([$comment_id_to_delete]);
            $message[] = 'Komentar berhasil dihapus!';
        } else {
            $message[] = 'Komentar tidak ditemukan.';
        }
    } else {
        $message[] = 'ID komentar tidak valid.';
    }
}

$select_comments = $conn->prepare("SELECT c.*, ct.title AS content_title, u.name AS user_name 
                                    FROM `comments` c 
                                    JOIN content ct ON c.content_id = ct.id 
                                    JOIN users u ON c.user_id = u.id 
                                    ORDER BY c.id DESC");
$select_comments->execute();

if ($select_comments->rowCount() == 0) {
    $message[] = 'Tidak ada komentar yang ditemukan.';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Komentar</title>
    <link rel="stylesheet" href="../css/admin_style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <?php include '../components/manage_header.php'; ?>

    <section class="manage-comments">
        <h1 class="heading">Data Komentar</h1>

        <div class="table-container" style="max-width: 100%; overflow-x: auto; margin: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <thead>
                    <tr
                        style="background-color:rgb(192, 192, 192); color: #333; text-align: left; border-bottom: 2px solid #ccc;">
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Konten
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Komentar
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Pengguna
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td data-label="Konten"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_comment['content_title']); ?>
                        </td>
                        <td data-label="Komentar"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_comment['comment']); ?>
                        </td>
                        <td data-label="Pengguna"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_comment['user_name']); ?>
                        </td>
                        <td data-label="Aksi"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <form action="" method="post" onsubmit="return confirm('Hapus komentar ini?');"
                                style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>" />
                                <button type="submit" name="delete_comment"
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