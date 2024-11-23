<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdbno1";

// ตรวจสอบว่ามีการส่งข้อมูลฟอร์มมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // การเชื่อมต่อกับฐานข้อมูล
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // รับข้อมูลจากฟอร์ม
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    if ($password != $confirm_password) {
        die("Passwords do not match.");
    }

    // ตรวจสอบว่ามีชื่อผู้ใช้งานที่ซ้ำกันหรือไม่
    $sql_check_username = "SELECT * FROM users WHERE username='$username'";
    $result_check_username = $conn->query($sql_check_username);
    
    if ($result_check_username->num_rows > 0) {
        echo "<script>alert('ชื่อนี้ได้ถูกใช้แล้ว'); history.back();</script>";
        exit();
    }
    
    if (isset($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }

    // เข้ารหัสรหัสผ่านก่อนบันทึกลงในฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // เตรียมคำสั่ง SQL
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

    // ส่งคำสั่ง SQL ไปยังฐานข้อมูล
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('คุณได้สร้างบัญชีเรียบร้อยแล้ว');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . "<br>" . $conn->error . "');</script>";
    }

    // ปิดการเชื่อมต่อ
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        input[type="text"], input[type="password"] {
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
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message{
            color: red;
        }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 87px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            text-decoration: none; 
}

        .button:hover {
            background-color: #45a049;
}

        
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
        <h2>สมัคร</h2>
        <label for="username">ชื่อผู้ใช้งาน:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">พาสเวิร์ด:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="confirm_password">ยืนยันพาสเวิร์ด:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>
        <input type="submit" value="ลงทะเบียน">
        <p><a href="login.php" class ="button">กลับไปหน้าล๊อคอิน</a></p>

    </form>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;
            if (password != confirm_password) {
                alert("พาสเวิร์ด ไม่เหมือนกัน");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
