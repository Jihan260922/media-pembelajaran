<?php
include '../components/connect.php';

if (!isset($_COOKIE['admin_id'])) {
    header('location:adm_log.php');
    exit;
}

$admin_id = $_COOKIE['admin_id'];

$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

$count_siswa = $conn->prepare("SELECT * FROM `users`");
$count_siswa->execute();
$total_siswa = $count_siswa->rowCount();

$count_tutors = $conn->prepare("SELECT * FROM `tutors`");
$count_tutors->execute();
$total_tutors = $count_tutors->rowCount();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <?php include '../components/manage_header.php'; ?>

    <section class="tutor-profile" style="min-height: calc(100vh - 19rem);">

        <h1 class="heading">Profil Detail</h1>

        <div class="details">
            <div class="tutor">
                <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="Foto Profil">
                <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
                <a href="adm_edit_profile.php?id=<?= $fetch_profile['id']; ?>" class="inline-btn">Ubah Profil</a>
            </div>
            <div class="flex">
                <div class="box">
                    <span><?= $total_siswa; ?></span>
                    <p>Total Siswa</p>
                    <a href="manage_users.php" class="btn">Lihat Siswa</a>
                </div>
                <div class="box">
                    <span><?= $total_tutors; ?></span>
                    <p>Total Tutor</p>
                    <a href="manage_tutors.php" class="btn">Lihat Tutor</a>
                </div>

            </div>
        </div>

    </section>

    <?php include '../components/footer.php'; ?>

    <script src="../js/admin_script.js"></script>

</body>

</html>