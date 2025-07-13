<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
    header('location:login.php');
    exit; 
}

$count_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
$count_likes->execute([$user_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$count_comments->execute([$user_id]);
$total_comments = $count_comments->rowCount();

$count_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$count_bookmark->execute([$user_id]);
$total_bookmark = $count_bookmark->rowCount();

$select_announcement = $conn->prepare("SELECT * FROM `announcements` ORDER BY id DESC LIMIT 1");
$select_announcement->execute();
$announcement = $select_announcement->fetch(PDO::FETCH_ASSOC);

if (!$announcement) {
    $announcement = [
        'image' => 'default_image.jpg', 
        'title' => 'Tidak ada pengumuman',
        'description' => 'Belum ada pengumuman terbaru.'
    ];
}

$select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_user->execute([$user_id]);
$user_data = $select_user->fetch(PDO::FETCH_ASSOC);
$user_name = $user_data ? htmlspecialchars($user_data['name']) : 'Pengguna'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
    .announcements .box {
        background-color: var(--white);
        border-radius: .5rem;
        padding: 3.5rem;
        text-align: center;
        overflow: hidden;
    }

    .announcements .box img {
        width: 100%;
        height: 550px;
        border-radius: 0.5rem;
        object-fit: cover;
    }

    .announcements .box h3 {
        text-align: left;
        font-size: 24px;
        padding-bottom: 10px;
        padding-top: 10px;
    }

    .announcements .box p {
        text-align: left;
        font-size: 20px;
        margin-top: auto;
        color: #888;
    }
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="quick-select">
        <h1 class="heading">Selamat datang - <?= $user_name; ?></h1>
        <div class="box-container">
            <div class="box">
                <h1 class="title">Daftar Playlist</h1>
                <div class="flex">
                    <a href="courses.php"><i class="fas fa-quran"></i><span>Tahfidz</span></a>
                    <a href="courses.php"><i class="fas fa-pray"></i><span>Do'a Harian</span></a>
                    <a href="courses.php"><i class="fas fa-landmark"></i><span>Fiqih</span></a>
                    <a href="courses.php"><i class="fas fa-running"></i><span>Olahraga</span></a>
                    <a href="courses.php"><i class="fas fa-calculator"></i><span>Berhitung</span></a>
                    <a href="courses.php"><i class="fas fa-sort-alpha-asc"></i><span>Membaca</span></a>
                    <a href="courses.php"><i class="fas fa-child-reaching"></i><span>Menari</span></a>
                </div>
            </div>

            <div class="box">
                <h1 class="title">Materi Tambahan</h1>
                <div class="flex">
                    <a href="https://www.coolmathgames.com/"><i class="fas fa-cubes"></i><span>Matematika Pintar
                        </span></a>
                    <a href="https://www.starfall.com/h/"><i class="fas fa-microphone"></i><span>Bernyanyi</span></a>
                    <a href="https://kids.nationalgeographic.com/games"><i class="fas fa-puzzle-piece"></i><span>Game
                            Puzzle</span></a>
                    <a href="https://www.sesamestreet.org/art-maker"><i class="fas fa-paint-brush"></i><span>Belajar
                            Melukis</span></a>
                    <a href="https://www.sesamestreet.org/home"><i class="fas fa-star"></i><span>Bermain
                            sambil belajar</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="announcements">
        <h1 class="heading">Pengumuman</h1>
        <div class="box-container">
            <div class="box">
                <?php if ($announcement): ?>
                <img src="uploaded_files/<?= htmlspecialchars($announcement['image']); ?>"
                    alt="<?= htmlspecialchars($announcement['title']); ?>">
                <h3><?= htmlspecialchars($announcement['title']); ?></h3>
                <p><?= htmlspecialchars($announcement['description']); ?></p>
                <?php else: ?>
                <p class="empty">Tidak ada pengumuman terbaru!</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>
</body>

</html>