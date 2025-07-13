<?php
include '../components/connect.php';

if(isset($_COOKIE['tutor_id'])){
    $tutor_id = $_COOKIE['tutor_id'];
}else{
    $tutor_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $password_verify = $conn->prepare("SELECT * FROM `tutors` WHERE email = ? LIMIT 1");
    $password_verify->execute([$email]);
    $row = $password_verify->fetch(PDO::FETCH_ASSOC);

    if($password_verify->rowCount() > 0){
        if (password_verify($pass, $row['password'])) {
            setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:dashboard.php');
            exit;
        } else {
            $message[] = 'Password atau email anda salah!';
        }
    } else {
        $message[] = 'Password atau email anda salah!';
    }   
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Guru</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body style="padding-left: 0;">
    <?php
    if (isset($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message form">
                <span>' . $msg . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
    ?>
    <section class="form-container">
        <form action="" method="post">
            <h3>Login Guru</h3>
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