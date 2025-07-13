    <?php
    $db_name = 'mysql:host=localhost;dbname=elearning_db';
    $db_user_name = 'root';
    $db_user_pass = '';

    try {
        $conn = new PDO($db_name, $db_user_name, $db_user_pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Koneksi database gagal: " . $e->getMessage());
    }

    function create_unique_id() {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $rand = array();
        $length = strlen($str) - 1;
        for ($i = 0; $i < 20; $i++) {
            $n = mt_rand(0, $length);
            $rand[] = $str[$n];
        }
        return implode('', $rand);
    }
    ?>