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

function showMessage($message, $redirect = "user_page.php") {
    echo "<script>
            alert('$message');
            window.location.href = '$redirect';
          </script>";
}

// Check if status column exists, if not add it
$checkColumn = $conn->query("SHOW COLUMNS FROM reservations LIKE 'status'");
if ($checkColumn->num_rows == 0) {
    // Add status column if it doesn't exist
    $conn->query("ALTER TABLE reservations ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'insert') {
        $name = sanitize_input($_POST['name']);
        $yrandsection = sanitize_input($_POST['yrandsection']);
        $roomno = sanitize_input($_POST['roomno']);
        $reservation_date = sanitize_input($_POST['reservation_date']);
        $reservation_time = sanitize_input($_POST['reservation_time']);
        
        // Check if this room is already reserved for the same time and date
        $checkStmt = $conn->prepare("SELECT * FROM reservations WHERE roomno = ? AND reservation_date = ? AND reservation_time = ? AND status = 'confirmed'");
        $checkStmt->bind_param("sss", $roomno, $reservation_date, $reservation_time);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            showMessage("This room is already reserved for the selected time and date. Please choose another time slot.");
        } else {
            // Insert the reservation with pending status
            $stmt = $conn->prepare("INSERT INTO reservations (name, yrandsection, roomno, reservation_time, reservation_date, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("sssss", $name, $yrandsection, $roomno, $reservation_time, $reservation_date);
            
            if ($stmt->execute()) {
                showMessage("Your reservation has been submitted and is pending approval by an administrator.");
            } else {
                showMessage("Error submitting reservation: " . $conn->error);
            }
            $stmt->close();
        }
        $checkStmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Processing Reservation</title>
    <link rel="stylesheet" href="/style/reservation.css">
    <link rel="icon" type="image/x-icon" href="images/cmulogo.png">
    <script type="text/javascript" src="/app.js" defer></script>

</head>
<body>
    <div>
        <h2>Processing your reservation...</h2>
        <p>You will be redirected to the home page.</p>
    </div>
</body>
</html>