<?php
include 'components/connect.php';

session_start();

// Hapus pemeriksaan login
// if (!isset($_COOKIE['user_id']) || empty($_COOKIE['user_id'])) {
//     header('location:login.php');
//     exit;
// }

// $user_id = $_COOKIE['user_id'];
// $get_user = $conn->prepare("SELECT * FROM users WHERE id = ?");
// $get_user->execute([$user_id]);
// $user_data = $get_user->fetch(PDO::FETCH_ASSOC);

// $user_class = isset($user_data['class']) ? strtoupper(trim($user_data['class'])) : '';
// if (!in_array($user_class, ['A', 'B'])) {
//     die("Error: Kelas tidak valid. Silahkan hubungi administrator.");
// }

// Ambil semua playlist yang aktif
$get_playlists = $conn->prepare("SELECT p.*, t.name as tutor_name, t.image as tutor_image, t.profession as tutor_profession 
                                FROM playlist p
                                JOIN tutors t ON p.tutor_id = t.id
                                WHERE p.status = 'active' 
                                ORDER BY p.date DESC");
$get_playlists->execute();
$playlists = $get_playlists->fetchAll(PDO::FETCH_ASSOC);

// Ambil total rating untuk setiap playlist
foreach ($playlists as &$playlist) {
    $playlist_id = $playlist['id'];
    $select_ratings = $conn->prepare("SELECT 
    SUM(happy_count) as happy_count, 
    SUM(very_happy_count) as very_happy_count, 
    SUM(neutral_count) as neutral_count, 
    SUM(sad_count) as sad_count, 
    SUM(angry_count) as angry_count 
    FROM ratings 
    WHERE content_id IN (SELECT id FROM content WHERE playlist_id = ?)");
    $select_ratings->execute([$playlist_id]);

    $rating_counts = $select_ratings->fetch(PDO::FETCH_ASSOC);

    // Hitung total rating
    $total_rating = ($rating_counts['happy_count'] ?? 0) +
        ($rating_counts['very_happy_count'] ?? 0) +
        ($rating_counts['neutral_count'] ?? 0) +
        ($rating_counts['sad_count'] ?? 0) +
        ($rating_counts['angry_count'] ?? 0);

    $playlist['total_rating'] = $total_rating; // Simpan total rating ke dalam array playlist
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Kelas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
    .rating {
        font-size: 16px;
        color: #888;
    }

    .emoji-label {
        display: inline-block;
        margin: 0 5px;
    }

    .emoji {
        font-size: 32px;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="courses">
        <h1 class="heading">Daftar Kategori Playlist</h1>

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
                </h3>
                <p class="rating">Total Rating: <?= $playlist['total_rating']; ?>
                </p> <!-- Menampilkan total rating -->
                <a href="playlist.php?get_id=<?= $playlist['id'] ?>" class="inline-btn">Lihat Playlist</a>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="empty-container">
                <img src="images/no-data.svg" alt="No data" style="width: 200px;">
                <p>Belum ada playlist tersedia.</p>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'tutor'): ?>
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