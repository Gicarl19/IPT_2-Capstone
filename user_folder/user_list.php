<?php 
session_start();  

if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Reservations</title>
    <link rel="stylesheet" href="/style/user/user_list.css">
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
    <script type="text/javascript" src="/app.js" defer></script>
</head>
<body>
    <!-- Sidebar Navigation -->
    <nav id="sidebar">
        <ul>
            <li>
                <img src="/images/cmulogo.png" alt="Logo">
                <h1 class="sidebar">City Of Malabon University</h1>
            </li>

            <li>
                <a href="/room/room.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                        <path d="M3 2a1 1 0 0 1 1-1h4.5a1 1 0 0 1 1 1V14h-1V2.5a.5.5 0 0 0-.5-.5H4a.5.5 0 0 0-.5.5V14h-1V2Zm9 3h-2V1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-2V7h2V3Zm-2 8h2v2a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-2Z" />
                    </svg>
                    <span>&nbsp;&nbsp;Room</span>
                </a>
            </li>
            <li>
            <a href="/user_folder/reservation.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark-check" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z" />
                </svg>
                <span>&nbsp;&nbsp;Reservation</span>
            </a>
        </li>

            <li>
                <a href="/user_folder/user_list.php">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                        <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                        <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0" />
                    </svg>
                    <span>&nbsp;&nbsp;Confirmed Reservations</span>
                </a>
            </li>

            <li>
    <a href="/user_folder/report.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
            <path d="M5 7h6v1H5V7zm0 2h6v1H5V9z"/>
            <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zM13 5h-3.5a.5.5 0 0 1-.5-.5V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5z"/>
        </svg>
        <span>&nbsp;&nbsp;Report</span>
    </a>
</li>

<button onclick="toggleSubMenu(this)" class="dropdown-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0" />
                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z" />
            </svg>  
            <span>&nbsp;&nbsp;Settings</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-compact-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.553 6.776a.5.5 0 0 1 .67-.223L8 9.44l5.776-2.888a.5.5 0 1 1 .448.894l-6 3a.5.5 0 0 1-.448 0l-6-3a.5.5 0 0 1-.223-.67" />
            </svg>
        </button>
                <ul class="sub-menu">
                    <li><a href="/faqs.php">&nbsp;&nbsp;FAQ's</a></li>
                    <li><a href="/profilepage.php">&nbsp;&nbsp;Profile</a></li>
                    <li><a href="/logout.php">&nbsp;&nbsp;Log Out</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <main>
        <div id="successMessage"></div>

        <h1>Current Room Reservations</h1>
        <p>Only confirmed reservations are displayed below.</p>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "1234";
        $dbname = "user_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT name, purpose, roomno, reservation_time, reservation_date FROM reservation WHERE status = 'confirmed'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Name</th><th>Purpose</th><th>Room No</th><th>Day</th><th>Time Slot</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['name']}</td>
                    <td>{$row['purpose']}</td>
                    <td>{$row['roomno']}</td>
                    <td>{$row['reservation_time']}</td>
                    <td>{$row['reservation_date']}</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No confirmed reservations found.</p>";
        }

        $conn->close();
        ?>
    </main>
</body>
</html>
