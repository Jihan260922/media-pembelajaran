<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
    .swiper {
        width: 100%;
        height: 300px;
        margin-bottom: 1.5rem;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 1rem;
    }

    .about .row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 2rem;
    }

    .about .content {
        flex: 1 1 40rem;
    }
    </style>
</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="about">
        <div class="row">

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><img src="images/image1.jpg" alt=""></div>
                    <div class="swiper-slide"><img src="images/damkar.jpg" alt=""></div>
                    <div class="swiper-slide"><img src="images/image3.jpg" alt=""></div>
                    <div class="swiper-slide"><img src="images/image4.jpg" alt=""></div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>

            <div class="content">
                <h3>Kenapa harus menggunakan media pembelajaran berbasis video?</h3>
                <p>Media pembelajaran berbasis video ini menawarkan pengalaman belajar yang interaktif dan
                    fleksibel bagi siswa. Dengan sajian video menarik yang selaras dengan kurikulum, siswa
                    dapat menjelajahi materi pembelajaran kapan saja dan di mana saja, serta berinteraksi melalui fitur
                    komentar untuk pengalaman belajar yang lebih mendalam.</p>
                <a href="courses.php" class="inline-btn">Mulai belajar sekarang</a>
            </div>
        </div>

        <div class="box-container">

            <div class="box">
                <i class="fas fa-hand-pointer"></i>
                <div>
                    <h3>95%</h3>
                    <span>Interaktif</span>
                </div>
            </div>

            <div class="box">
                <i class="fas fa-play"></i>
                <div>
                    <h3>98%</h3>
                    <span>Video Menarik</span>
                </div>
            </div>

            <div class="box">
                <i class="fas fa-book"></i>
                <div>
                    <h3>96%</h3>
                    <span>Kurikulum</span>
                </div>
            </div>

            <div class="box">
                <i class="fas fa-sync-alt"></i>
                <div>
                    <h3>91%</h3>
                    <span>Fleksibel</span>
                </div>
            </div>

        </div>
    </section>


    <section class="reviews">
        <h1 class="heading">Visi, Misi dan Tujuan</h1>

        <div class="box-container">
            <div class="box">
                <h3 class="title">Visi</h3>
                <p>Visi kami adalah terwujudnya peserta didik yang mandiri, kreatif dan berakhlakul karimah.</p><br>

                <h3 class="title">Misi</h3>
                <p>Misi kami adalah membekali ilmu yang cukup, membentuk pribadi anak agar menjadi putra putri sholehah,
                    membimbing dan mengarahkan potensi anak didik supaya menjadi putra putri yang unggul dan pemberani,
                    serta membangun kepercayaan diri pada setiap anak.</p><br>

                <h3 class="title">Tujuan</h3>
                <p>Tujuan kami adalah mempersiapkan anak untuk memasuki jenjang sekolah berikutnya, membantu orang tua
                    dalam membentuk anak yang cerdas secara intelektual dan emosional, memiliki daya imajinasi, dan
                    mengembangkan sikap beragama serta pribadi yang mandiri, kreatif, dan berakhlak mulia.</p>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
    </script>

</body>

</html>