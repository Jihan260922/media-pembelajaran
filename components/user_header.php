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

<header class="header">

    <section class="flex">

        <a href="home.php" class="logo">
            <img src="images/logo.png" alt="E-Learning Logo" width="60" height="auto">
        </a>


        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
            <h3><?= $fetch_profile['name']; ?></h3>
            <span>Kelas <?= htmlspecialchars($fetch_profile['class']); ?></span>
            <!-- Menampilkan kelas yang dipilih dengan kata "Kelas:" -->
            <a href="profile.php" class="btn">Lihat profil</a>

            <a href="components/user_logout.php" onclick="return confirm('logout from this website?');"
                class="delete-btn">Logout</a>
            <?php
            } else {
            ?>
            <h3>Pilih Login atau Registrasi</h3>
            <div class="flex-btn">
                <a href="login.php" class="option-btn">Login</a>
                <a href="register.php" class="option-btn">Registrasi</a>
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
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
        ?>
        <img src="uploaded_files/<?= $fetch_profile['image']; ?>" alt="">
        <h3><?= $fetch_profile['name']; ?></h3>
        <span>Kelas <?= htmlspecialchars($fetch_profile['class']); ?></span>
        <!-- Menampilkan kelas yang dipilih dengan kata "Kelas:" -->
        <a href="profile.php" class="btn">Lihat profil</a>
        <?php
            } else {
        ?>
        <h3>Pilih Login atau Registrasi</h3>
        <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Registrasi</a>
        </div>
        <?php
            }
        ?>
    </div>

    <nav class="navbar">
        <a href="home.php"><i class="fas fa-home"></i><span>Beranda</span></a>
        <a href="about.php"><i class="fas fa-info-circle"></i><span>Tentang Kami</span></a>
        <a href="courses.php"><i class="fas fa-play-circle"></i><span>Playlist</span></a>
        <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>Daftar Guru</span></a>
        <a href="gallery.php"><i class="fas fa-images"></i><span>Galeri Kegiatan</span></a>
        <a href="contact.php"><i class="fas fa-headset"></i><span>Kontak</span></a>
    </nav>

</div>

<!-- side bar section ends -->