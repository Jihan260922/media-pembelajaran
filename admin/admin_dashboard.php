<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    $admin_id = $_COOKIE['admin_id'];
} else {
    $admin_id = '';
    header('location:login.php');
    exit;
}

$count_siswa = $conn->prepare("SELECT * FROM `users`");
$count_siswa->execute();
$total_siswa = $count_siswa->rowCount();

$count_tutors = $conn->prepare("SELECT * FROM `tutors`");
$count_tutors->execute();
$total_tutors = $count_tutors->rowCount();

$count_contents = $conn->prepare("SELECT * FROM `content`");
$count_contents->execute();
$total_contents = $count_contents->rowCount();

$count_messages = $conn->prepare("SELECT * FROM `contact`");
$count_messages->execute();
$total_messages = $count_messages->rowCount();

$count_gallery = $conn->prepare("SELECT * FROM `gallery`");
$count_gallery->execute();
$total_gallery = $count_gallery->rowCount();

$count_announcements = $conn->prepare("SELECT * FROM `announcements`");
$count_announcements->execute();
$total_announcements = $count_announcements->rowCount();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="dashboard">

        <h1 class="heading">Dashboard Admin</h1>

        <div class="box-container">

            <div class="box">
                <h3><?= $total_siswa; ?></h3>
                <p>Data Siswa Kelas A dan Kelas B</p>
                <a href="manage_users.php" class="btn">Total Siswa</a>
            </div>

            <div class="box">
                <h3><?= $total_tutors; ?></h3>
                <p>Data Guru</p>
                <a href="manage_tutors.php" class="btn">Total Guru</a>
            </div>

            <div class="box">
                <h3><?= $total_contents; ?></h3>
                <p>Data Konten Video</p>
                <a href="manage_contens.php" class="btn">Total Konten</a>
            </div>

            <div class="box">
                <h3><?= $total_messages; ?></h3>
                <p>Total Pesan Masuk</p>
                <a href="manage_pesan.php" class="btn">Lihat Pesan</a>
            </div>

            <div class="box">
                <h3><?= $total_gallery; ?></h3>
                <p>Total Galeri Kegiatan</p>
                <a href="manage_gallery.php" class="btn">Lihat Galeri</a>
            </div>

            <div class="box">
                <h3><?= $total_announcements; ?></h3>
                <p>Total Pengumuman</p>
                <a href="manage_announcement.php" class="btn">Lihat Pengumuman</a>
            </div>

        </div>

    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>