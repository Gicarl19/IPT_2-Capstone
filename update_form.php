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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idreservations'])) {
    $idreservations = $_POST['idreservations'];

    // Fetch the existing data for the given reservation ID
    $sql = "SELECT idreservations, name, yrandsection, roomno, reservation_time, reservation_date FROM reservations WHERE idreservations=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idreservations);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Close the statement
    $stmt->close();
}
?>
<head>
    <title>Update Reservation</title>
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
    <link rel="stylesheet" href="/style/update_form.css"> 
</head>

<body>
<div class="form-box">
    <h2>Update Reservation</h2>
<form method="POST" action="process_reservations.php">
    <input type="hidden" name="idreservations" value="<?php echo $row['idreservations']; ?>">
    <input type="hidden" name="action" value="update">
    <label for="name">Name: </label>
    <input type="text" name="name" value="<?php echo $row['name']; ?>"><br>
    <label for="yrandsection">Year and Section: </label>
    <input type="text" name="yrandsection" value="<?php echo $row['yrandsection']; ?>"><br>
    <label for="roomno">Room No: </label>
    <input type="text" name="roomno" value="<?php echo $row['roomno']; ?>"><br>
    <label for="reservation_time">Time of Reservation: </label>
    <input type="time" name="reservation_time" value="<?php echo $row['reservation_time']; ?>"><br>
    <label for="reservation_date">Date of Reservation: </label>
    <input type="date" name="reservation_date"  value="<?php echo $row['reservation_date']; ?>">
    <input type="submit" value="Update">
</form>
</body>
</div>
<?php
// Close the connection
$conn->close();
?>
