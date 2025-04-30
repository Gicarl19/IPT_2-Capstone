<div>
    <button class="back-to-home">
        <a href="/ad_folder/admin_page.php">Back</a></button>
</div>
<?php
$servername = "localhost";
$username = "root";
$password = "#0000";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$checkColumn = $conn->query("SHOW COLUMNS FROM reservations LIKE 'status'");
if ($checkColumn->num_rows == 0) {
    $conn->query("ALTER TABLE reservations ADD COLUMN status VARCHAR(20) DEFAULT 'pending'");
}

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != true) {
    $sql = "SELECT idreservations, name, yrandsection, roomno, reservation_time, reservation_date, status 
            FROM reservations 
            WHERE status = 'confirmed'";
} 
else {
    $sql = "SELECT idreservations, name, yrandsection, roomno, reservation_time, reservation_date, status 
            FROM reservations";
}

$result = $conn->query($sql);



if ($result->num_rows > 0) {
    echo "<table>
          <tr>
            <th>Reservation ID</th>
            <th>Name</th>
            <th>Year & Section</th>
            <th>Room No</th>
            <th>Reservation Date</th>
            <th>Reservation Time</th>";
    
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
        echo "<th>Status</th><th>Actions</th>";
    }
    

    echo "</tr>";


    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
              <td>{$row['idreservations']}</td>
              <td>{$row['name']}</td>
              <td>{$row['yrandsection']}</td>
              <td>{$row['roomno']}</td>
              <td>{$row['reservation_date']}</td>
              <td>{$row['reservation_time']}</td>";
        
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
            echo "<td>{$row['status']}</td>
                  <td>
                    <form method='POST' style='display:inline-block;'>
                        <input type='hidden' name='idreservations' value='{$row['idreservations']}'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Delete'>
                    </form>
                    <form method='POST' action='admin_update.php' style='display:inline-block;'>
                        <input type='hidden' name='idreservations' value='{$row['idreservations']}'>
                        <input type='submit' value='Update'>
                    </form>";
            
            if ($row['status'] != 'confirmed') {
                echo "<form method='POST' action='admin_confirm.php' style='display:inline-block;'>
                        <input type='hidden' name='idreservations' value='{$row['idreservations']}'>
                        <input type='submit' value='Confirm'>
                      </form>";
            }
            
            echo "</td>";
        }
        
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found.";
}

$conn->close();


?>

<link rel="stylesheet" href="/style/updt.css">