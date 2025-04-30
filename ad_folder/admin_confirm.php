<?php 
session_start();  

if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

?>  
<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idreservations'])) {
    $id = sanitize_input($_POST['idreservations']);

    // Check if 'status' column exists
    $check = $conn->query("SHOW COLUMNS FROM reservations LIKE 'status'");
    if ($check->num_rows == 0) {
        // If not, add the column (optional, you can remove this block if your table already has it)
        $conn->query("ALTER TABLE reservations ADD status VARCHAR(20) DEFAULT 'pending'");
    }

    // Update status to confirmed
    $stmt = $conn->prepare("UPDATE reservations SET status = 'confirmed' WHERE idreservations = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Reservation Confirmed!');
                window.location.href = '/ad_folder/admin_list.php'; // Change to the correct return page if different
              </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirming Reservation</title>
    <link rel="stylesheet" href="/style/list.css">
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
</head>
<body>
    <div>
        <h2>Processing reservation confirmation...</h2>
        <p>You will be redirected back to the reservation list shortly.</p>
    </div>
</body>
</html>
