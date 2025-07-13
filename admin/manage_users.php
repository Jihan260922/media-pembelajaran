<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$tutor_id = $_COOKIE['admin_id'];
$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    if (isset($_POST['user_id']) && is_numeric($_POST['user_id'])) {
        $user_id = (int) $_POST['user_id'];

        $check_user = $conn->prepare("SELECT id FROM `users` WHERE id = ?");
        $check_user->execute([$user_id]);

        if ($check_user->rowCount() > 0) {
            $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
            $delete_user->execute([$user_id]);
            $message[] = 'Pengguna berhasil dihapus!';
        } else {
            $message[] = 'Pengguna tidak ditemukan.';
        }
    } else {
        $message[] = 'ID pengguna tidak valid.';
    }
}

$class_filter = isset($_POST['class']) ? $_POST['class'] : '';

if ($class_filter) {
    $select_users = $conn->prepare("SELECT * FROM `users` WHERE class = ?");
    $select_users->execute([$class_filter]);
} else {
    $select_users = $conn->prepare("SELECT * FROM `users`");
    $select_users->execute();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="manage-users">
        <h1 class="heading">Data Siswa</h1>

        <form action="" method="post" style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
            <select name="class" class="box" required onchange="this.form.submit();"
                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc; margin-right: 10px; font-size: 1.2rem; width: 200px;">
                <option value="" disabled selected>-- Pilih Kelas --</option>
                <option value="A" <?= ($class_filter == 'A') ? 'selected' : ''; ?>>Kelas A</option>
                <option value="B" <?= ($class_filter == 'B') ? 'selected' : ''; ?>>Kelas B</option>
            </select>
        </form>


        <div style="display: flex; justify-content: flex-end; margin: 20px;">
            <form action="../register.php" method="get">
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
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Tanggal
                            Registrasi</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Nama
                            Siswa</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Kelas
                        </th>

                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Email
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetch_user = $select_users->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= date('d-m-Y H:i', strtotime($fetch_user['created_at'])); ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= htmlspecialchars($fetch_user['name']); ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= htmlspecialchars($fetch_user['class']); ?>
                        </td>

                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px;">
                            <?= htmlspecialchars($fetch_user['email']); ?>
                        </td>
                        <td style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <a href="edit_user.php?user_id=<?= $fetch_user['id']; ?>" title="Edit"
                                style="margin-right: 10px;">
                                <i class="fas fa-edit" style="color: #007bff; font-size: 20px;"></i>
                            </a>
                            <form action="" method="post" onsubmit="return confirm('Hapus pengguna ini?');"
                                style="display:inline;">
                                <input type="hidden" name="user_id" value="<?= $fetch_user['id']; ?>">
                                <button type="submit" name="delete_user"
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