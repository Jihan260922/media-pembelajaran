<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tutor'])) {
    if (isset($_POST['tutor_id']) && is_numeric($_POST['tutor_id'])) {
        $tutor_id_to_delete = (int) $_POST['tutor_id'];
        $check_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
        $check_tutor->execute([$tutor_id_to_delete]);

        if ($check_tutor->rowCount() > 0) {
            $select_videos = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
            $select_videos->execute([$tutor_id_to_delete]);
            while ($fetch_video = $select_videos->fetch(PDO::FETCH_ASSOC)) {
                unlink('../uploaded_files/' . $fetch_video['thumb']);
                unlink('../uploaded_files/' . $fetch_video['video']);
            }
            $delete_content = $conn->prepare("DELETE FROM `content` WHERE tutor_id = ?");
            $delete_content->execute([$tutor_id_to_delete]);
            $delete_tutor = $conn->prepare("DELETE FROM `tutors` WHERE id = ?");
            $delete_tutor->execute([$tutor_id_to_delete]);
            $message[] = 'Tutor dan konten terkait berhasil dihapus!';
        } else {
            $message[] = 'Tutor tidak ditemukan.';
        }
    } else {
        $message[] = 'ID tutor tidak valid.';
    }
}

$select_tutors = $conn->prepare("SELECT * FROM `tutors`");
$select_tutors->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Guru</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php include '../components/manage_header.php'; ?>

    <section class="manage-tutors">
        <h1 class="heading">Data Guru</h1>

        <div style="display: flex; justify-content: flex-end; margin: 20px;">
            <form action="register.php" method="get">
                <button type="submit" name="submit" class="btn"
                    style="background-color: green; color: white; padding: 8px 15px; border: none; cursor: pointer; font-size: 16px; border-radius: 4px;">
                    Tambah
                </button>
            </form>
        </div>
        <div class="table-container" style="max-width: 100%; overflow-x: auto; margin: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <thead>
                    <tr
                        style="background-color:rgb(192, 192, 192); color: #333; text-align: left; border-bottom: 2px solid #ccc;">
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Foto
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Guru
                            Kelas</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Nama
                            Guru</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Email
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td data-label="Foto"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <img src="../uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>"
                                alt="Foto Tutor" style="width: 50px; height: 50px; border-radius: 50%;">
                        </td>
                        <td data-label="Tutor Kelas" style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= htmlspecialchars($fetch_tutor['profession']); ?>
                        </td>
                        <td data-label="Nama" style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= htmlspecialchars($fetch_tutor['name']); ?>
                        </td>
                        <td data-label="Email" style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= htmlspecialchars($fetch_tutor['email']); ?>
                        </td>
                        <td data-label="Aksi"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <a href="edit_tutor.php?id=<?= $fetch_tutor['id']; ?>"
                                style="margin-right: 10px; text-decoration: none;">
                                <i class="fas fa-edit" style="color: #007bff; font-size: 20px;" title="Edit"></i>
                            </a>
                            <form action="" method="post" onsubmit="return confirm('Hapus tutor ini?');"
                                style="display:inline;">
                                <input type="hidden" name="tutor_id" value="<?= $fetch_tutor['id']; ?>">
                                <button type="submit" name="delete_tutor"
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