<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $user_data = $select_user->fetch(PDO::FETCH_ASSOC);
    $user_class = $user_data ? htmlspecialchars($user_data['class']) : '';
} else {
    $user_id = '';
    $user_class = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="teachers">

        <h1 class="heading">Daftar Guru Kelas <?= htmlspecialchars($user_class); ?></h1>

        <form action="search_tutor.php" method="post" class="search-tutor">
            <input type="text" name="search_tutor" maxlength="100" placeholder="Cari guru..." required>
            <button type="submit" name="search_tutor_btn" class="fas fa-search"></button>
        </form>

        <div class="box-container">

            <?php
            $select_tutors = $conn->prepare("SELECT * FROM `tutors` WHERE class = ?");
            $select_tutors->execute([$user_class]);
            if ($select_tutors->rowCount() > 0) {
                while ($fetch_tutor = $select_tutors->fetch(PDO::FETCH_ASSOC)) {

                    $tutor_id = $fetch_tutor['id'];

                    $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
                    $count_playlists->execute([$tutor_id]);
                    $total_playlists = $count_playlists->rowCount();

                    $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
                    $count_contents->execute([$tutor_id]);
                    $total_contents = $count_contents->rowCount();

                    $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
                    $count_likes->execute([$tutor_id]);
                    $total_likes = $count_likes->rowCount();

                    $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
                    $count_comments->execute([$tutor_id]);
                    $total_comments = $count_comments->rowCount();
            ?>
            <div class="box">
                <div class="tutor">
                    <img src="uploaded_files/<?= htmlspecialchars($fetch_tutor['image']); ?>" alt="">
                    <div>
                        <h3><?= htmlspecialchars($fetch_tutor['name']); ?></h3>
                        <span><?= htmlspecialchars($fetch_tutor['profession']); ?></span>
                    </div>
                </div>
                <p>Playlist : <span><?= $total_playlists; ?></span></p>
                <p>Total videos : <span><?= $total_contents ?></span></p>
                <p>Total suka : <span><?= $total_likes ?></span></p>
                <p>Total komentar : <span><?= $total_comments ?></span></p>
                <form action="tutor_profile.php" method="post">
                    <input type="hidden" name="tutor_email" value="<?= htmlspecialchars($fetch_tutor['email']); ?>">
                    <input type="submit" value="Lihat profil" name="tutor_fetch" class="inline-btn">
                </form>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">Tidak ada guru yang terdaftar untuk kelas ini!</p>';
            }
            ?>

        </div>

    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>