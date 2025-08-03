<?php
include 'components/connect.php';

$user_id = ''; // Tidak perlu lagi menggunakan user_id

if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id'];
} else {
    $get_id = '';
    header('location:about.php');
}

// Menangani rating konten
if (isset($_POST['rate_content'])) {
    // Hapus pemeriksaan login
    $content_id = $_POST['content_id'];
    $rating_value = $_POST['rating_value']; // Ambil nilai rating dari form
    $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);
    $rating_value = filter_var($rating_value, FILTER_SANITIZE_STRING);

    // Tentukan jumlah untuk setiap emoticon
    $happy_count = 0;
    $very_happy_count = 0;
    $neutral_count = 0;
    $sad_count = 0;
    $angry_count = 0;

    switch ($rating_value) {
        case '😊':
            $happy_count = 1; // Senang
            break;
        case '😍':
            $very_happy_count = 1; // Sangat Senang
            break;
        case '😐':
            $neutral_count = 1; // Netral
            break;
        case '😢':
            $sad_count = 1; // Sedih
            break;
        case '😡':
            $angry_count = 1; // Marah
            break;
    }

    $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
    $select_content->execute([$content_id]);
    $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

    $tutor_id = $fetch_content['tutor_id'];

    // Insert rating baru
    $insert_rating = $conn->prepare("INSERT INTO `ratings`(tutor_id, content_id, happy_count, very_happy_count, neutral_count, sad_count, angry_count) VALUES(?,?,?,?,?,?,?)");
    $insert_rating->execute([$tutor_id, $content_id, $happy_count, $very_happy_count, $neutral_count, $sad_count, $angry_count]);

    $message[] = 'Rating ditambahkan!';
}

// Menangani komentar
if (isset($_POST['add_comment'])) {
    $id = create_unique_id();
    $comment_box = $_POST['comment_box'];
    $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);
    $content_id = $_POST['content_id'];
    $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);
    $name = $_POST['name']; // Ambil nama pengguna dari input
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
    $select_content->execute([$content_id]);
    $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

    $tutor_id = $fetch_content['tutor_id'];

    if ($select_content->rowCount() > 0) {
        $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment, name) VALUES(?,?,?,?,?,?)");
        $insert_comment->execute([$id, $content_id, $user_id, $tutor_id, $comment_box, $name]);
        $message[] = 'Komentar berhasil dibuat';
    } else {
        $message[] = 'something went wrong!';
    }
}

if (isset($_POST['delete_comment'])) {
    $delete_id = $_POST['comment_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
    $verify_comment->execute([$delete_id]);

    if ($verify_comment->rowCount() > 0) {
        $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
        $delete_comment->execute([$delete_id]);
        $message[] = 'Komentar berhasil dihapus!';
    } else {
        $message[] = 'Komentar sudah dihapus!';
    }
}

if (isset($_POST['update_now'])) {
    $update_id = $_POST['update_id'];
    $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
    $update_box = $_POST['update_box'];
    $update_box = filter_var($update_box, FILTER_SANITIZE_STRING);

    $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ?");
    $verify_comment->execute([$update_id, $update_box]);

    if ($verify_comment->rowCount() > 0) {
        $message[] = 'Komentar sudah dibuat!';
    } else {
        $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
        $update_comment->execute([$update_box, $update_id]);
        $message[] = 'Komentar berhasil di update!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memutar video</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
    .emoji-label {
        display: inline-block;
        margin: 5px;
    }

    .emoji {
        font-size: 32px;
        cursor: pointer;
    }
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <?php
    if (isset($_POST['edit_comment'])) {
        $edit_id = $_POST['comment_id'];
        $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);
        $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? LIMIT 1");
        $verify_comment->execute([$edit_id]);
        if ($verify_comment->rowCount() > 0) {
            $fetch_edit_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);
    ?>
    <section class="edit-comment">
        <h1 class="heading">Edit komentar</h1>
        <form action="" method="post">
            <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
            <textarea name="update_box" class="box" maxlength="1000" required placeholder="please enter your comment"
                cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
            <div class="flex">
                <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">Batal edit</a>
                <input type="submit" value="Update sekarang" name="update_now" class="inline-btn">
            </div>
        </form>
    </section>
    <?php
        } else {
            $message[] = 'comment was not found!';
        }
    }
    ?>

    <section class="watch-video">

        <?php
        $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? AND status = ?");
        $select_content->execute([$get_id, 'active']);
        if ($select_content->rowCount() > 0) {
            while ($fetch_content = $select_content->fetch(PDO::FETCH_ASSOC)) {
                $content_id = $fetch_content['id'];

                // Ambil jumlah rating untuk masing-masing emoticon
                $select_ratings = $conn->prepare("SELECT 
                    SUM(happy_count) as happy_count, 
                    SUM(very_happy_count) as very_happy_count, 
                    SUM(neutral_count) as neutral_count, 
                    SUM(sad_count) as sad_count, 
                    SUM(angry_count) as angry_count 
                    FROM `ratings` WHERE content_id = ?");
                $select_ratings->execute([$content_id]);
                $rating_counts = $select_ratings->fetch(PDO::FETCH_ASSOC);

                $select_tutor = $conn->prepare("SELECT * FROM `tutors` WHERE id = ? LIMIT 1");
                $select_tutor->execute([$fetch_content['tutor_id']]);
                $fetch_tutor = $select_tutor->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="video-details">
            <video src="uploaded_files/<?= $fetch_content['video']; ?>" class="video"
                poster="uploaded_files/<?= $fetch_content['thumb']; ?>" controls autoplay></video>
            <h3 class="title"><?= $fetch_content['title']; ?></h3>
            <div class="info">
                <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>

                <p>
                    <span>😊: <?= $rating_counts['happy_count']; ?></span>
                    <span>😍: <?= $rating_counts['very_happy_count']; ?></span>
                    <span>😐: <?= $rating_counts['neutral_count']; ?></span>
                    <span>😢: <?= $rating_counts['sad_count']; ?></span>
                    <span>😡: <?= $rating_counts['angry_count']; ?></span>
                </p>
            </div>
            <div class="tutor">
                <img src="uploaded_files/<?= $fetch_tutor['image']; ?>" alt="">
                <div>
                    <h3><?= $fetch_tutor['name']; ?></h3>
                    <span><?= $fetch_tutor['profession']; ?></span>
                </div>
            </div>
            <form action="" method="post" class="rating-form">
                <input type="hidden" name="content_id" value="<?= $content_id; ?>">
                <div class="emoji-options">
                    <label class="emoji-label">
                        <input type="radio" name="rating_value" value="😊">
                        <span class="emoji">😊</span>
                    </label>
                    <label class="emoji-label">
                        <input type="radio" name="rating_value" value="😍">
                        <span class="emoji">😍</span>
                    </label>
                    <label class="emoji-label">
                        <input type="radio" name="rating_value" value="😐">
                        <span class="emoji">😐</span>
                    </label>
                    <label class="emoji-label">
                        <input type="radio" name="rating_value" value="😢">
                        <span class="emoji">😢</span>
                    </label>
                    <label class="emoji-label">
                        <input type="radio" name="rating_value" value="😡">
                        <span class="emoji">😡</span>
                    </label>
                </div>
                <input type="submit" name="rate_content" value="Kirim" class="submit-btn">
            </form>

            <div class="description">
                <p><?= $fetch_content['description']; ?></p>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Tidak ada video yang tersedia</p>';
        }
        ?>

    </section>

    <section class="comments">

        <h1 class="heading">Tambahkan komentar</h1>

        <form action="" method="post" class="add-comment">
            <input type="hidden" name="content_id" value="<?= $get_id; ?>">
            <input type="text" name="name" required placeholder="Masukkan nama" maxlength="100" cols="100"
                rows="50"><br><br>
            <textarea name="comment_box" required placeholder="Tuliskan komentar..." maxlength="1000" cols="30"
                rows="10"></textarea>
            <input type="submit" value="Tambah komentar" name="add_comment" class="inline-btn">
        </form>

        <h1 class="heading">Daftar Komentar</h1>

        <div class="show-comments">
            <?php
            $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
            $select_comments->execute([$get_id]);
            if ($select_comments->rowCount() > 0) {
                while ($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)) {
                    // Tampilkan nama yang diinputkan
                    $name = htmlspecialchars($fetch_comment['name']);
            ?>
            <div class="box">
                <div class="user">
                    <div>
                        <h3><?= $name; ?></h3>
                        <span><?= $fetch_comment['date']; ?></span>
                    </div>
                </div>
                <p class="text"><?= htmlspecialchars($fetch_comment['comment']); ?></p>
                <?php
                        if ($fetch_comment['user_id'] == $user_id) {
                        ?>
                <form action="" method="post" class="flex-btn">
                    <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
                    <button type="submit" name="edit_comment" class="inline-option-btn">Edit komentar</button>
                    <button type="submit" name="delete_comment" class="inline-delete-btn"
                        onclick="return confirm('delete this comment?');">Hapus komentar</button>
                </form>
                <?php
                        }
                        ?>
            </div>
            <?php
                }
            } else {
                echo '<p class="empty">Tidak ada komentar yang diunggah</p>';
            }
            ?>
        </div>

    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>