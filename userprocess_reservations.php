<!DOCTYPE html>
<html>
<head>
    <title>Reservation List</title>
    <style>
       table {
            margin: auto;
            border-collapse: collapse;
            width: 90%;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            background: white;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dddddd;
            font-size: 16px;
        }

        th {
            background-color:#012362;
            color: white;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        td {
            color: #333333;
            font-size: 15px;
        }

        caption {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .button {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin: 15px;
        }

        button {
            background-color:#012362;
            color: white;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #005fa3;
        }

        button a {
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        #successMessage {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            visibility: hidden;
        }

        #successMessage.show {
            opacity: 1;
            visibility: visible;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
</head>
<body>
<div class="button">
    <button><a href="user_page.php">Back</a></button>
</div>

<div id="successMessage"></div>

<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if ($action == 'insert') {
        $name = sanitize_input($_POST['name']);
        $yrandsection = sanitize_input($_POST['yrandsection']);
        $roomno = sanitize_input($_POST['roomno']);
        $reservation_time = sanitize_input($_POST['reservation_time']);
        $reservation_date = sanitize_input($_POST['reservation_date']);
        
        $stmt = $conn->prepare("INSERT INTO reservations (name, yrandsection, roomno, reservation_time, reservation_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $yrandsection, $roomno, $reservation_time, $reservation_date);
        $stmt->execute();
        showMessage("Record Inserted successfully");
        $stmt->close();
    }
    
    if ($action == 'update') {
        $idreservations = sanitize_input($_POST['idreservations']);
        $name = sanitize_input($_POST['name']);
        $yrandsection = sanitize_input($_POST['yrandsection']);
        $roomno = sanitize_input($_POST['roomno']);
        $reservation_time = sanitize_input($_POST['reservation_time']);
        $reservation_date = sanitize_input($_POST['reservation_date']);
        
        $stmt = $conn->prepare("UPDATE reservations SET name=?, yrandsection=?, roomno=?, reservation_time=?, reservation_date=? WHERE idreservations=?");
        $stmt->bind_param("sssssi", $name, $yrandsection, $roomno, $reservation_time, $reservation_date, $idreservations);
        $stmt->execute();
        showMessage("Record Updated successfully");
        $stmt->close();
    }
    
    if ($action == 'delete') {
        $idreservations = sanitize_input($_POST['idreservations']);
        $stmt = $conn->prepare("DELETE FROM reservations WHERE idreservations=?");
        $stmt->bind_param("i", $idreservations);
        $stmt->execute();
        
        $result = $conn->query("SELECT COUNT(*) as count FROM reservations");
        $row = $result->fetch_assoc();
        if ($row['count'] == 0) {
            $conn->query("ALTER TABLE reservations AUTO_INCREMENT = 1");
        }
        showMessage("Record Deleted successfully");
        $stmt->close();
    }
}

$sql = "SELECT idreservations, name, yrandsection, roomno, reservation_time, reservation_date FROM reservations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>Reservation ID</th><th>Name</th><th>Year & Section</th><th>Room No</th><th>Reservation Time</th><th>Reservation Date</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
        <td>{$row['idreservations']}</td>
        <td>{$row['name']}</td>
        <td>{$row['yrandsection']}</td>
        <td>{$row['roomno']}</td>
        <td>{$row['reservation_time']}</td>
        <td>{$row['reservation_date']}</td>
        </td></tr>";
    }
    echo "</table>";
    
    } else {
        echo '<div style="display: flex; justify-content: center; align-items: center; height: 100vh; font-size: 1.5rem; color: #555;">No results found.</div>';
    }
    
    

$conn->close();

function showMessage($message) {
    echo "<script>
            let msg = document.getElementById('successMessage');
            msg.innerText = '$message';
            msg.classList.add('show');
            setTimeout(function() {
                msg.classList.remove('show');
            }, 1000);
          </script>";
}
?>
</body>
</html>
