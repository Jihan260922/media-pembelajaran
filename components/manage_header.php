<?php
// Ambil data profil admin
$select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_profile->execute([$_COOKIE['admin_id']]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<?php if (isset($message)): ?>
<?php foreach ($message as $msg): ?>
<div class="message">
    <span><?= htmlspecialchars($msg) ?></span>
    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Header Section Start -->
<header class="header">
    <section class="flex">
        <a href="manage_users.php" class="logo">Selamat Datang Admin</a>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars" style="cursor: pointer;"></div>
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <?php if ($fetch_profile): ?>
            <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="Foto Profil">
            <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>

            <a href="admin_profile.php" class="btn">Lihat Profil</a> <!-- Tombol Lihat Profil Ditambahkan -->
            <a href="../components/adm_out.php" onclick="return confirm('logout from this website?');"
                class="delete-btn">Logout</a>
            <?php else: ?>
            <h3>Profil Tidak Ditemukan</h3>
            <?php endif; ?>
        </div>
    </section>
</header>
<!-- Header Section Ends -->

<!-- Sidebar Section Start -->
<div class="side-bar">
    <div class="close-side-bar">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php if ($fetch_profile): ?>
        <img src="../uploaded_files/<?= $fetch_profile['image']; ?>" alt="Foto Profil">
        <h3><?= htmlspecialchars($fetch_profile['name']); ?></h3>

        <a href="admin_profile.php" class="btn">Lihat Profil</a> <!-- Tombol Lihat Profil Ditambahkan -->
        <?php else: ?>
        <h3>Profil Tidak Ditemukan</h3>
        <?php endif; ?>
    </div>

    <nav class="navbar">
        <a href="admin_dashboard.php">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
        </a>
        <a href="manage_announcement.php">
            <i class="fas fa-bullhorn"></i>
            <span>Data Pengumuman</span>
        </a>
        <a href="manage_gallery.php">
            <i class="fas fa-images"></i>
            <span>Data Galeri Kegiatan</span>
        </a>
        <a href="manage_users.php">
            <i class="fas fa-users"></i>
            <span>Data Siswa</span>
        </a>
        <a href="manage_tutors.php">
            <i class="fas fa-chalkboard-teacher"></i>
            <span>Data Guru</span>
        </a>
        <a href="manage_contens.php">
            <i class="fas fa-video"></i>
            <span>Data Konten Video</span>
        </a>
        <a href="manage_pesan.php">
            <i class="fas fa-message"></i>
            <span>Data Pesan</span>
        </a>

    </nav>
</div>
<!-- Sidebar Section Ends -->