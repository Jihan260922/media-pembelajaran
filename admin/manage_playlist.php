<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}


$tutor_id = $_COOKIE['admin_id'];
$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_playlist'])) {
    if (isset($_POST['playlist_id']) && is_numeric($_POST['playlist_id'])) {
        $playlist_id_to_delete = (int) $_POST['playlist_id'];
        $check_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE id = ?");
        $check_playlist->execute([$playlist_id_to_delete]);
        if ($check_playlist->rowCount() > 0) {
            $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE id = ?");
            $delete_playlist->execute([$playlist_id_to_delete]);
            $message[] = 'Playlist berhasil dihapus!';
        } else {
            $message[] = 'Playlist tidak ditemukan.';
        }
    } else {
        $message[] = 'ID playlist tidak valid.';
    }
}

$select_playlists = $conn->prepare("SELECT p.*, t.name AS tutor_name FROM `playlist` p JOIN `tutors` t ON p.tutor_id = t.id");
$select_playlists->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Data Playlist</title>
    <link rel="stylesheet" href="../css/admin_style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php include '../components/manage_header.php'; ?>

    <section class="manage-playlists">
        <h1 class="heading">Data Playlist</h1>

        <div class="table-container" style="max-width: 100%; overflow-x: auto; margin: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <thead>
                    <tr
                        style="background-color: rgb(192, 192, 192); color: #333; text-align: left; border-bottom: 2px solid #ccc;">
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Judul
                            Playlist</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            Deskripsi</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Status
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Nama
                            Tutor</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetch_playlist = $select_playlists->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_playlist['title']); ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_playlist['description']); ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?php
                                $status = htmlspecialchars($fetch_playlist['status']);
                                $color = ($status === 'active') ? 'green' : (($status === 'deactive') ? 'red' : 'black');
                                ?>
                            <span style="color: <?= $color; ?>; font-weight: bold;"><?= $status; ?></span>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_playlist['tutor_name']); ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <form action="" method="post" onsubmit="return confirm('Hapus playlist ini?');"
                                style="display:inline;">
                                <input type="hidden" name="playlist_id" value="<?= $fetch_playlist['id']; ?>" />
                                <button type="submit" name="delete_playlist"
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