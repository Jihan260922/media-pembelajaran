<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0){
        if (password_verify($pass, $row['password'])) {
            setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
            header('location:home.php');
            exit;
        } else {
            $message[] =  'Password atau email anda salah!';
        }
    } else {
        $message[] =  'Password atau email anda salah!';
    }   
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
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
            <h3>Login Siswa</h3>
            <p>Email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="Masukkan email" class="box">
            <p>Password <span>*</span></p>
            <input type="password" name="pass" maxlength="20" required placeholder="Masukkan password" class="box">
            <input type="submit" value="Masuk Sekarang" name="submit" class="btn">
        </form>
    </section>

    <script src="js/script.js"></script>
</body>

</html>