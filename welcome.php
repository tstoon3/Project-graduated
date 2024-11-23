<?php
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่ ถ้าไม่ได้เข้าสู่ระบบให้เปลี่ยนเส้นทางไปยังหน้า login.php
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// การเชื่อมต่อกับ MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdbno1";
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงชื่อผู้ใช้งานจาก Session
$username = $_SESSION["username"];

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลบันทึกทั้ง 20 รายการ
$sql = "SELECT username, RPG_File1, RPG_File2, RPG_File3, RPG_File4, RPG_File5, RPG_File6, RPG_File7, RPG_File8, RPG_File9, RPG_File10, RPG_File11, RPG_File12, RPG_File13, RPG_File14, RPG_File15, RPG_File16, RPG_File17, RPG_File18, RPG_File19, RPG_File20, created_at, last_login FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // แสดงข้อมูลผู้ใช้งาน
    $row = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome</title>
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
            .container {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                width: 300px;
            }
            h1, h2, p {
                margin: 0 0 10px;
            }
            .save-data {
                margin-top: 20px;
            }
            .save-data p {
                margin: 0;
            }
            .button {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
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
        <div class="container">
            <h1>Welcome, <?php echo $row["username"]; ?></h1>
            <p>Created at: <?php echo $row["created_at"]; ?></p>
            <p>Last login: <?php echo $row["last_login"]; ?></p>
            <div class="save-data">
                <!--<h2>Saved</h2>-->
                <?php
                // แสดงข้อมูลบันทึก
              // แสดงข้อมูลบันทึก
// แสดงข้อมูลบันทึก
//for ($i = 1; $i <= 20; $i++) {
  //  $saveField = "RPG_File" . str_pad($i, STR_PAD_LEFT);
    //$icon = (!empty($row[$saveField])) ? '✔️' : '❌';
    //echo " <p>$saveField: $icon</p>";
//}


                ?>
            </div>
            <div class="button-container">
            <button class="button" onclick="deleteRPGFiles(); redirectToFolder()">เข้าสู่เกม</button>
        </div>
        </div>
        <script>
           
        function redirectToFolder() {
            window.location.href = 'www';
        }
        function deleteRPGFiles() {
    
             for (var i = 1; i <= 20; i++) {
        var key = "RPG File" + i;
        localStorage.removeItem(key);
        }
    console.log("Deleted RPG Files from local storage.");
            }

</script>
      
    </body>
    </html>
    <?php
} else {
    echo "ไม่พบผู้ใช้";
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>