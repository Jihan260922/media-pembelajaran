<?php
include 'components/connect.php';

session_start();

if (!isset($_COOKIE['user_id']) || empty($_COOKIE['user_id'])) {
    header('location:login.php');
    exit;
}

$user_id = $_COOKIE['user_id'];
$get_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
$get_user->execute([$user_id]);
$user_data = $get_user->fetch(PDO::FETCH_ASSOC);

$user_class = isset($user_data['class']) ? strtoupper(trim($user_data['class'])) : '';
if (!in_array($user_class, ['A', 'B'])) {
    die("Error: Kelas tidak valid. Silahkan hubungi administrator.");
}

$get_playlists = $conn->prepare("SELECT p.*, t.name as tutor_name, t.image as tutor_image, t.profession as tutor_profession 
                                FROM playlist p
                                JOIN tutors t ON p.tutor_id = t.id
                                WHERE p.status = 'active' 
                                AND (p.class = :class OR p.class IS NULL)
                                ORDER BY p.date DESC");
$get_playlists->execute([':class' => $user_class]);
$playlists = $get_playlists->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Kelas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="courses">
        <h1 class="heading">Daftar Playlist Kelas <?= htmlspecialchars($user_class); ?></h1>

        <div class="box-container">
            <?php if (count($playlists) > 0): ?>
            <?php foreach ($playlists as $playlist): ?>
            <div class="box">
                <div class="tutor">
                    <img src="uploaded_files/<?= htmlspecialchars($playlist['tutor_image']) ?>" alt="Tutor">
                    <div>
                        <h3><?= htmlspecialchars($playlist['tutor_name']) ?></h3>
                        <span><?= htmlspecialchars($playlist['tutor_profession']) ?></span>

                    </div>
                </div>
                <img src="uploaded_files/<?= htmlspecialchars($playlist['thumb']) ?>" class="thumb" alt="Thumbnail">
                <h3 class="title">
                    <?= htmlspecialchars($playlist['title']) ?>
                    <?php if ($playlist['class']): ?>
                    <?php endif; ?>
                </h3>

                <a href="playlist.php?get_id=<?= $playlist['id'] ?>" class="inline-btn">Lihat Playlist</a>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="empty-container">
                <img src="images/no-data.svg" alt="No data" style="width: 200px;">
                <p>Belum ada playlist tersedia untuk Kelas <?= $user_class ?></p>
                <?php if ($_SESSION['user_role'] == 'tutor'): ?>
                <a href="add_playlist.php" class="inline-btn">Buat Playlist Baru</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>