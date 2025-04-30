<?php

$conn = new mysqli('localhost', 'root', '1234', 'user_db');


if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = $_POST['userName'];
    $userComments = $_POST['userComments'];

$targetDir = __DIR__ . "/uploads/";
$userPicture = basename($_FILES['userPicture']['name']);
$targetFile = $targetDir . $userPicture;


if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true); 
}


if (move_uploaded_file($_FILES['userPicture']['tmp_name'], $targetFile)) {
    $stmt = $conn->prepare("INSERT INTO reports (userName, userPicture, userComments) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $userName, $userPicture, $userComments);
    $stmt->execute();
    echo '
<div style="position: relative; height: 100vh; margin: 0; font-family: Arial, sans-serif; background-color: #f0f2f5;">

    <!-- Home Button -->
    <a href="/user_folder/report.php" style="
        position: absolute;
        top: 20px;
        right: 30px;
        padding: 10px 20px;
        background-color: #007BFF;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    " onmouseover="this.style.backgroundColor=\'#0056b3\'" onmouseout="this.style.backgroundColor=\'#007BFF\'">Home</a>

    <!-- Centered Message -->
    <div style="
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        font-size: 24px;
        color: #28a745;
        font-weight: bold;
    ">
        Report submitted successfully!
    </div>

</div>';

    $stmt->close();
} else {
    echo "Error uploading the file.";
}
}

$conn->close();
?>