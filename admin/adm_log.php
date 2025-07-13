<?php
include '../components/connect.php';

if (isset($_COOKIE['admin_id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];

    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE email = ?");
    $select_admin->execute([$email]);

    if ($select_admin->rowCount() > 0) {
    $admin = $select_admin->fetch(PDO::FETCH_ASSOC);
    if (password_verify($pass, $admin['password'])) {
        setcookie('admin_id', $admin['id'], time() + 60 * 60 * 24 * 30, '/');
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $message[] = 'Password salah!';
        header('Location: adm_log.php');
        exit;
    }
} else {
    $message[] = 'Email tidak ditemukan!';
    header('Location: adm_log.php'); 
    exit;
}

}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body style="padding-left: 0;">
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '<div class="message form"><span>' . htmlspecialchars($msg) . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
        }
    }
    ?>

    <section class="form-container">
        <form action="" method="post">
            <h3>Login Admin</h3>
            <p>Email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="Masukkan email" class="box">
            <p>Password <span>*</span></p>
            <input type="password" name="pass" maxlength="20" required placeholder="Masukkan password" class="box">
            <input type="submit" value="Masuk Sekarang" name="submit" class="btn">
        </form>
    </section>

    <script src="../js/admin_script.js"></script>
</body>

</html>