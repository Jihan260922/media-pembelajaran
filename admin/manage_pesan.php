<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    if (isset($_POST['message_id']) && is_numeric($_POST['message_id'])) {
        $message_id_to_delete = (int)$_POST['message_id'];

        $check_message = $conn->prepare("SELECT * FROM `contact` WHERE id = ?");
        $check_message->execute([$message_id_to_delete]);

        if ($check_message->rowCount() > 0) {
            $delete_message = $conn->prepare("DELETE FROM `contact` WHERE id = ?");
            $delete_message->execute([$message_id_to_delete]);
            $message[] = 'Pesan berhasil dihapus!';
        } else {
            $message[] = 'Pesan tidak ditemukan.';
        }
    } else {
        $message[] = 'ID pesan tidak valid.';
    }
}

$select_messages = $conn->prepare("SELECT * FROM `contact` ORDER BY id DESC");
$select_messages->execute();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pesan</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body>
    <?php include '../components/manage_header.php'; ?>

    <section class="manage-messages">
        <h1 class="heading">Data Pesan</h1>

        <div class="table-container" style="max-width: 100%; overflow-x: auto; margin: 20px;">
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
                <thead>
                    <tr
                        style="background-color: rgb(192, 192, 192); color: #333; text-align: left; border-bottom: 2px solid #ccc;">
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Nama
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Email
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">No
                            Telepon</th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Pesan
                        </th>
                        <th style="padding: 15px; border: 1px solid #ccc; font-size: 16px; text-align: center;">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tr style="border-bottom: 1px solid #ddd;">
                        <td data-label="Nama"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_message['name']); ?>
                        </td>
                        <td data-label="Email"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_message['email']); ?>
                        </td>
                        <td data-label="No Telepon"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_message['number']); ?>
                        </td>
                        <td data-label="Pesan"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <?= htmlspecialchars($fetch_message['message']); ?>
                        </td>
                        <td data-label="Aksi"
                            style="padding: 10px; border: 1px solid #ccc; font-size: 16px; text-align: center;">
                            <form action="" method="post" onsubmit="return confirm('Hapus pesan ini?');"
                                style="display:inline;">
                                <input type="hidden" name="message_id" value="<?= $fetch_message['id']; ?>" />
                                <button type="submit" name="delete_message"
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