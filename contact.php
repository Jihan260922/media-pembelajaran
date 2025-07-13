<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

    $select_contact = $conn->prepare("SELECT * FROM `contact` WHERE email = ? AND message = ?");
    $select_contact->execute([$email, $msg]);

    if ($select_contact->rowCount() > 0) {
        $message[] = 'Pesan sudah terkirim sebelumnya!';
    } else {
        $insert_message = $conn->prepare("INSERT INTO `contact`(name, email, number, message) VALUES(?,?,?,?)");
        $insert_message->execute([$name, $email, $number, $msg]);
        $message[] = 'Pesan terkirim!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'components/user_header.php'; ?>

    <section class="contact">
        <div class="row">
            <div class="image">
                <img src="images/kontak.png" alt="">
            </div>

            <form action="" method="post">
                <h3>Hubungi Kami</h3>
                <input type="text" placeholder="Masukan nama orang tua" required maxlength="100" name="name"
                    class="box">
                <input type="email" placeholder="Masukan email orang tua" required maxlength="100" name="email"
                    class="box">
                <input type="number" min="0" max="9999999999" placeholder="Masukan no telepon orang tua" required
                    maxlength="10" name="number" class="box">
                <textarea name="msg" class="box" placeholder="Masukan pesan" required cols="30" rows="10"
                    maxlength="1000"></textarea>
                <input type="submit" value="Kirim pesan" class="inline-btn" name="submit">
            </form>
        </div>

        <div class="box-container">
            <div class="box">
                <i class="fas fa-phone"></i>
                <h3>Telepon</h3>
                <a href="tel:+62 812-8365-3438">+62 812-8365-3438</a>
            </div>

            <div class="box">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <a href="mailto:ranurulh@gmail.com">ranurulh@gmail.com</a>
            </div>

            <div class="box">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Alamat</h3>
                <a href="https://maps.app.goo.gl/q5d1sELxj4a5Rfps7">Jl. Kujang No. 199 Cijurey Rt.003 Rw.003 Desa
                    Kujangsari Kec. Langensari Kota Banjar Jawa
                    Barat</a>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>