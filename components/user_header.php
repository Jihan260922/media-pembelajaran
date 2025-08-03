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

        <h1 class="heading1">Selamat Datang di Media Pembelajaran Interaktif</h1>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
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
        <a href="home.php" class="logo">
            <img src="images/logo.png" alt="E-Learning Logo" width="40" height="auto">
        </a>
        <h3>RA Nurul Huda</h3>


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