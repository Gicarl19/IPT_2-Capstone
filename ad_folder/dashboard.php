<?php 
session_start();  

if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Dashboard</title>
    <link rel="stylesheet" href="/style/process.css">
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
    <script type="text/javascript" src="/app.js" defer></script>
    <style>
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .dashboard-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            flex: 1;
            min-width: 250px;
        }
        .dashboard-card h2 {
            color: #4b6cb7;
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .stats {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .reservation-form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-group textarea {
            height: 100px;
        }
        .form-submit {
            background-color: #4b6cb7;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-submit:hover {
            background-color: #395591;
        }
        .recent-list {
            margin-top: 20px;
            max-height: 300px;
            overflow-y: auto;
        }
        .recent-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .recent-list th, .recent-list td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .recent-list th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<nav id="sidebar">
    <ul>
        <img src="/images/cmulogo.png" alt="Logo"> <h1 class="sidebar">College of Business Administration</h1>
        <li>
            <a href="/ad_folder/admin_List.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
            </svg>
            <span>&nbsp&nbsp&nbspBooked List</span>
            </a>
        </li>
        <li>
    <a href="/ad_folder/admin_dashboard.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-check" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
            <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z"/>
        </svg>
        <span>&nbsp;&nbsp;&nbsp;Ad Verification</span>
    </a>
</li>
        <li>
            <a href="/ad_room/ad_room.php"> 
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-check" viewBox="0 0 16 16">
                    <path d="M7.293 1.5a1 1 0 0 1 1.414 0L11 3.793V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v3.293l2.354 2.353a.5.5 0 0 1-.708.708L8 2.207l-5 5V13.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 2 13.5V8.207l-.646.647a.5.5 0 1 1-.708-.708z"/>
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.707l.547.547 1.17-1.951a.5.5 0 1 1 .858.514"/>
                </svg>
                <span>&nbsp&nbsp&nbspRoom Check</span>
            </a>
        </li>
        <li>
            <a href="/ad_folder/admin_process.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-check" viewBox="0 0 16 16">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zm-2 3v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3h-14zm10.854 3.646a.5.5 0 0 0-.708 0L7.5 9.793 5.854 8.146a.5.5 0 1 0-.708.708l2 2a.5.5 0 0 0 .708 0l4-4a.5.5 0 0 0 0-.708z"/>
                </svg>
                <span>&nbsp&nbsp&nbspProcess Reservation</span>
            </a>
        </li>
        <li>
            <a href="/ad_folder/admin_record.php" class="active">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                </svg>
                <span>&nbsp&nbsp&nbspRecord Reservation</span>
            </a>
        </li>
        <li>
            <a href="/ad_folder/admin_report.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                    <path d="M5.5 5.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5z"/>
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3-.5a1 1 0 0 1-1-1V1.5L9.5 0H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4h-2z"/>
                </svg>
                <span>&nbsp;&nbsp;Reports</span>
            </a>
        </li>
        <button onclick="toggleSubMenu(this)" class="dropdown-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
            </svg>
            <span>&nbsp;&nbsp;&nbsp;Settings</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.553 6.776a.5.5 0 0 1 .67-.223L8 9.44l5.776-2.888a.5.5 0 1 1 .448.894l-6 3a.5.5 0 0 1-.448 0l-6-3a.5.5 0 0 1-.223-.67"/>
            </svg>
        </button>

        <ul class="sub-menu">
            <li><a href="/logout.php">&nbsp;&nbsp;&nbsp;Log Out</a></li>
        </ul>
    </ul>
</nav>

<!-- Main Content Area -->
<main>
    <h1>Reservation Dashboard</h1>

    <div id="successMessage"></div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "#0000";
    $dbname = "user_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        $action = $_POST['action'];
        
        if ($action == 'insert') {
            $name = sanitize_input($_POST['name']);
            $purpose = sanitize_input($_POST['purpose']);
            $roomno = sanitize_input($_POST['roomno']);
            $reservation_time = sanitize_input($_POST['reservation_time']);
            $reservation_date = sanitize_input($_POST['reservation_date']);
            
            $stmt = $conn->prepare("INSERT INTO reservations (name, purpose, roomno, reservation_time, reservation_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $purpose, $roomno, $reservation_time, $reservation_date);
            $stmt->execute();
            showMessage("Reservation recorded successfully");
            $stmt->close();
        }
    }

    // Get statistics for dashboard
    $totalReservations = $conn->query("SELECT COUNT(*) as total FROM reservations")->fetch_assoc()['total'];
    $todayReservations = $conn->query("SELECT COUNT(*) as today FROM reservations WHERE reservation_date = CURDATE()")->fetch_assoc()['today'];
    $upcomingReservations = $conn->query("SELECT COUNT(*) as upcoming FROM reservations WHERE reservation_date > CURDATE()")->fetch_assoc()['upcoming'];
    
    // Get room statistics
    $roomStats = $conn->query("SELECT roomno, COUNT(*) as count FROM reservations GROUP BY roomno ORDER BY count DESC LIMIT 5");
    
    // Get available rooms for dropdown
    $roomsResult = $conn->query("SELECT DISTINCT roomno FROM reservations ORDER BY roomno");
    $rooms = [];
    while ($room = $roomsResult->fetch_assoc()) {
        $rooms[] = $room['roomno'];
    }
    ?>

    <!-- Dashboard Statistics -->
    <div class="dashboard-container">
        <div class="dashboard-card">
            <h2>Total Reservations</h2>
            <div class="stats"><?php echo $totalReservations; ?></div>
        </div>
        <div class="dashboard-card">
            <h2>Today's Reservations</h2>
            <div class="stats"><?php echo $todayReservations; ?></div>
        </div>
        <div class="dashboard-card">
            <h2>Upcoming Reservations</h2>
            <div class="stats"><?php echo $upcomingReservations; ?></div>
        </div>
        <div class="dashboard-card">
            <h2>Most Booked Rooms</h2>
            <ul>
                <?php 
                if ($roomStats->num_rows > 0) {
                    while ($room = $roomStats->fetch_assoc()) {
                        echo "<li>{$room['roomno']}: {$room['count']} bookings</li>";
                    }
                } else {
                    echo "<li>No data available</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <!-- Reservation Form -->
    <div class="reservation-form">
        <h2>Record New Reservation</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="insert">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="purpose">Purpose:</label>
                <textarea id="purpose" name="purpose" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="roomno">Room Number:</label>
                <select id="roomno" name="roomno" required>
                    <option value="">Select a room</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?php echo $room; ?>"><?php echo $room; ?></option>
                    <?php endforeach; ?>
                    <option value="other">Other (Enter Below)</option>
                </select>
            </div>
            
            <div class="form-group" id="otherRoomContainer" style="display: none;">
                <label for="otherRoom">Other Room:</label>
                <input type="text" id="otherRoom" name="otherRoom">
            </div>
            
            <div class="form-group">
                <label for="reservation_time">Reservation Time:</label>
                <input type="time" id="reservation_time" name="reservation_time" required>
            </div>
            
            <div class="form-group">
                <label for="reservation_date">Reservation Date:</label>
                <input type="date" id="reservation_date" name="reservation_date" required>
            </div>
            
            <button type="submit" class="form-submit">Record Reservation</button>
        </form>
    </div>

    <!-- Recent Reservations -->
    <div class="dashboard-card">
        <h2>Recent Reservations</h2>
        <div class="recent-list">
            <?php
            $recentReservations = $conn->query("SELECT idreservations, name, purpose, roomno, reservation_time, reservation_date FROM reservations ORDER BY idreservations DESC LIMIT 10");
            
            if ($recentReservations->num_rows > 0) {
                echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Room</th>
                        <th>Time</th>
                        <th>Date</th>
                    </tr>";
                
                while ($row = $recentReservations->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['idreservations']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['roomno']}</td>
                        <td>{$row['reservation_time']}</td>
                        <td>{$row['reservation_date']}</td>
                    </tr>";
                }
                
                echo "</table>";
            } else {
                echo "<p>No recent reservations found.</p>";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

</main>

<script>
function toggleSubMenu(button) {
    const subMenu = button.nextElementSibling;
    subMenu.classList.toggle('active');

    const dropdownIcon = button.querySelector('.bi-chevron-compact-down');
    if (dropdownIcon) {
        dropdownIcon.style.transform = subMenu.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
    }
}

// Handle "Other" room selection
document.getElementById('roomno').addEventListener('change', function() {
    const otherRoomContainer = document.getElementById('otherRoomContainer');
    if (this.value === 'other') {
        otherRoomContainer.style.display = 'block';
        document.getElementById('otherRoom').setAttribute('required', 'required');
    } else {
        otherRoomContainer.style.display = 'none';
        document.getElementById('otherRoom').removeAttribute('required');
    }
});

// Handle form submission for "Other" room
document.querySelector('form').addEventListener('submit', function(e) {
    const roomSelect = document.getElementById('roomno');
    const otherRoomInput = document.getElementById('otherRoom');
    
    if (roomSelect.value === 'other' && otherRoomInput.value.trim() !== '') {
        roomSelect.innerHTML += `<option value="${otherRoomInput.value}">${otherRoomInput.value}</option>`;
        roomSelect.value = otherRoomInput.value;
    }
});

const currentPath = window.location.pathname;
const links = document.querySelectorAll('#sidebar a');

links.forEach(link => {
  if (link.getAttribute('href') === currentPath) {
    link.classList.add('active');
  }
});
</script>

<?php
function showMessage($message) {
    echo "<script>
            let msg = document.getElementById('successMessage');
            msg.innerText = '$message';
            msg.classList.add('show');
            setTimeout(function() {
                msg.classList.remove('show');
            }, 3000);
          </script>";
}
?>

</body>
</html>