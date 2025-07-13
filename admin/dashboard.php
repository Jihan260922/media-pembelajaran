<?php

include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
   $tutor_id = $_COOKIE['tutor_id'];
}else{
   $tutor_id = '';
   header('location:login.php');
   
}

$count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$count_contents->execute([$tutor_id]);
$total_contents = $count_contents->rowCount();

$count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$count_playlists->execute([$tutor_id]);
$total_playlists = $count_playlists->rowCount();

$count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$count_likes->execute([$tutor_id]);
$total_likes = $count_likes->rowCount();

$count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$count_comments->execute([$tutor_id]);
$total_comments = $count_comments->rowCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>


    <?php include '../components/admin_header.php'; ?>

    <section class="dashboard">

        <h1 class="heading">Dashboard</h1>

        <div class="box-container">

            <div class="box">
                <h3><?= $total_playlists; ?></h3>
                <p>Upload Playlist</p>
                <a href="add_playlist.php" class="btn">Tambah playlist baru</a>
            </div>

            <div class="box">
                <h3><?= $total_contents; ?></h3>
                <p>Upload Konten</p>
                <a href="add_content.php" class="btn">Tambah konten baru</a>
            </div>

            <div class="box">
                <h3><?= $total_likes; ?></h3>
                <p>Total Like</p>
                <a href="contents.php" class="btn">Lihat like</a>
            </div>

            <div class="box">
                <h3><?= $total_comments; ?></h3>
                <p>Total Komentar</p>
                <a href="comments.php" class="btn">Lihat Komentar</a>
            </div>

        </div>

    </section>


    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>