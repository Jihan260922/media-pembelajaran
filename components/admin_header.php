<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- header section start  -->

<header class="header">

    <section class="flex">

        <?php
        // Ambil nama pengguna dari database
        $select_profile = $conn->prepare("SELECT * FROM `tutors` WHERE id = ?");
        $select_profile->execute([$tutor_id]);
        if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            $user_name = $fetch_profile['name']; // Ambil nama pengguna
        } else {
            $user_name = "Guru"; // Nama default jika tidak ditemukan
        }
        ?>

        <a href="dashboard.php" class="logo">Selamat Datang <?= htmlspecialchars($user_name); ?></a>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php
            if($select_profile->rowCount() > 0){
            ?>
            <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="">
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
            <span><?= htmlspecialchars($fetch_profile['profession']); ?></span>
            <a href="profile.php" class="btn">Lihat profil</a>

            <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"
                class="delete-btn">Logout</a>
            <?php
            } else {
            ?>
            <h3>Pilih masuk atau daftar</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Masuk</a>
                <a href="register.php" class="option-btn">Daftar</a>
            </div>
            <?php
            }
            ?>
        </div>

    </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar">

    <div class="close-side-bar">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php
            $select_profile->execute([$tutor_id]); // Mengambil data profil lagi untuk sidebar
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <img src="../uploaded_files/<?= htmlspecialchars($fetch_profile['image']); ?>" alt="">
        <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>
        <span><?= htmlspecialchars($fetch_profile['profession']); ?></span>
        <a href="profile.php" class="btn">Lihat profil</a>
        <?php
            } else {
        ?>
        <h3>Pilih masuk atau daftar</h3>
        <div class="flex-btn">
            <a href="login.php" class="option-btn">Masuk</a>
            <a href="register.php" class="option-btn">Daftar</a>
        </div>
        <?php
            }
        ?>
    </div>

    <nav class="navbar">
        <a href="dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a>
        <a href="playlists.php"><i class="fa-solid fa-bars-staggered"></i><span>Playlist</span></a>
        <a href="contents.php"><i class="fas fa-graduation-cap"></i><span>Konten</span></a>
        <a href="comments.php"><i class="fas fa-comment"></i><span>Komentar</span></a>
        <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');"><i
                class="fas fa-right-from-bracket"></i><span>Logout</span></a>
    </nav>

</div>

<!-- side bar section ends -->