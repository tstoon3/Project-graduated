<?php
session_start();

// การเชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdbno1";
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์มล็อกอิน
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // เข้ารหัสรหัสผ่านแบบ bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // สร้างคำสั่ง SQL เพื่อตรวจสอบข้อมูลล็อกอิน
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ข้อมูลล็อกอินถูกต้อง
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // เริ่ม session และเก็บชื่อผู้ใช้
            $_SESSION["username"] = $username;

            // ส่งผู้ใช้ไปยังหน้า welcome.php
            header("Location: welcome.php");

            exit();
        } else {
            $error_message = "ไม่พบชื่อหรือ Password ผิด";
        }
    } else {
        // ไม่พบข้อมูลผู้ใช้
        $error_message = "ไม่พบข้อมูลผู้ใช้";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('blur.png');
            background-repeat: no-repeat;
            background-attachment: fixed;  
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('backgroud.png');
            background-repeat: no-repeat;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        input[type="submit"]:hover,
        a:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h2>ล๊อคอิน</h2>
        <label for="username">ชื่อผู้ใช้งาน:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">พาสเวิร์ด:</label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="ล๊อคอิน">
        <a href="register.php">คุณมีบัญชีหรือยัง?</a>
        <?php
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>
    </form>
</body>

</html>
