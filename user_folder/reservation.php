<?php 
session_start();  

if (!isset($_SESSION['user_id']) && !isset($_SESSION['email'])) {
    header("Location: ../index.php");
    exit();
}

?>

<?php 
require_once '../config.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomno = $_POST['roomno'];
    $name = $_POST['name'];
    $purpose = $_POST['purpose']; // Changed from yrandsection to purpose
    $day = $_POST['reservation_date'];  
    $time = $_POST['reservation_time'];
    
    $currentDate = new DateTime(); 
    $currentDayOfWeek = $currentDate->format('l');
    $targetDate = clone $currentDate;
    $targetDate->modify('next ' . $day);  
    
    $reservationDate = $targetDate->format('Y-m-d');
    
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reservation (roomno, name, purpose, reservation_date, reservation_time) VALUES (?, ?, ?, ?, ?)");
    // Changed column name in the query from yrandsection to purpose
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sssss", $roomno, $name, $purpose, $reservationDate, $time);
    // Changed variable name from yrandsection to purpose in the bind_param
    
    if ($stmt->execute()) {
        echo "<script>alert('Reservation successful!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<?php

$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reservation System</title>
    <link rel="icon" href="/images/cmulogo.png">
    <link rel="stylesheet" href="/style/user/reservation.css">
    <script type="text/javascript" src="/app.js" defer></script>

</head>
<body>
    <nav id="sidebar">
    <ul>
    <img src="/images/cmulogo.png" alt="Logo" > <h1 class="sidebar">City Of Malabon University</h1>
    <li>
    <a href="/room/room.php">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
            <path d="M3 2a1 1 0 0 1 1-1h4.5a1 1 0 0 1 1 1V14h-1V2.5a.5.5 0 0 0-.5-.5H4a.5.5 0 0 0-.5.5V14h-1V2Zm9 3h-2V1h2a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-2V7h2V3Zm-2 8h2v2a1 1 0 0 1-1 1h-1a1 1 0 0 1-1-1v-2Z"/>
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
            <a href="user_list.php">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
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
            <div>
            <li><a href="/faqs.php">&nbsp;&nbsp;FAQ'S</a></li>
            <li><a href="/profilepage.php">&nbsp;&nbsp;Profile</a></li>
            <li><a href="/logout.php">&nbsp;&nbsp;Log Out</a></li>
            </div>
        </ul>

        
    </ul>
</nav>

<div class="container">
        <h1>CBA ROOMS AVAILABLE</h1>

        <div class="room-selection" id="room-selection">
            <?php
            $rooms = ["301", "302", "401", "402", "501", "502"];
            $floors = [];

            // Group rooms by floor
            foreach ($rooms as $room) {
                $floor = substr($room, 0, 1);
                $floors[$floor][] = $room;
            }

            // Output floor headings and room cards
            foreach ($floors as $floor => $floorRooms) {
                echo "<h2>Floor $floor</h2>";
                echo "<div class='floor-group'>";
                foreach ($floorRooms as $room) {
                    echo "
                    <div class='room-card' data-room='$room'>
                        <h3>CBA ROOM $room</h3>
                        <p class='room-details'>Capacity: " . ($room[0] == '3' ? 30 : 25) . "</p>
                        <p class='room-details'>Available: <span class='availability'>Yes</span></p>
                    </div>";
                }
                echo "</div>"; // close floor-group
            }
            ?>
        </div>
    </div>

    <!-- Reservation Modal --> 
    <div id="reservation-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Reserve a Room</h2>
            <form id="reservation-form" method="POST">
                <input type="hidden" id="roomno" name="roomno">
                
            <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="purpose">Purpose:</label>
                <select id="purpose" name="purpose" required>
                    <option value="">--Select Purpose--</option>
                    <option value="Class Session">Class Session</option>
                    <option value="Group Study">Group Study</option>
                    <option value="Meeting">Meeting</option>
                    <option value="Presentation">Presentation</option>
                    <option value="Special Event">Special Event</option>
                </select>

            <label for="reservation_date">Day:</label>
                <select id="day" name="reservation_date" onchange="showTimeSlot()" required>
                    <option value="">--Select Day--</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>

                <div id="timeSlotDiv" style="display:none;">
            <label for="reservation_time">Time Slot:</label>
                    <select id="reservation_time" name="reservation_time" required></select>
                </div>

                <br><button type="submit">Reserve Now</button>
                <div id="error-message" class="error"></div>
            </form>
        </div>
    </div>
</div>

<script>
    const roomCards = document.querySelectorAll('.room-card');
    const modal = document.getElementById('reservation-modal');
    const roomInput = document.getElementById('roomno');
    let selectedRoom = null;

    // Room + Day-specific time slots
    const timeSlots = {
        "301": {
            "Tuesday": ["4:00 PM - 5:30 PM"],
            "Wednesday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM","1:00 PM - 2:30 PM"],
            "Thursday": ["2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Friday": ["4:00 PM - 5:30 PM"],
            "Saturday": ["1:00 PM - 2:30 PM"],
            "Sunday": ["10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","1:00 PM - 2:30 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM","5:30 PM - 7:00 PM","7:00 PM - 8:30 PM"]
        },
        "302": {
            "Monday": ["10:00 AM - 11:30 AM"],
            "Tuesday": ["11:30 AM - 1:00 PM", "4:00 PM - 5:30 PM"],
            "Wednesday": ["10:00 AM - 11:30 AM"],
            "Thursday": ["10:00 AM - 11:30 AM"],
            "Friday": ["11:30 AM - 1:00 PM","4:00 PM - 5:30 PM","5:30 PM - 7:00 PM","7:00 PM - 8:30 PM"],
            "Saturday": ["1:00 PM - 2:30 PM",],
            "Sunday": ["7:00 AM - 8:30 AM","8:30 AM - 10:00 AM","1:00 PM - 2:30 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM","5:30 PM - 7:00 PM","7:00 PM - 8:30 PM"]
        },
        "401": {
            "Monday": ["8:30 AM - 10:00 AM","10:00 AM - 11:30 AM","2:30 PM - 4:00 PM"],
            "Tuesday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM"],
            "Wednesday": ["7:00 AM - 8:30 AM","8:30 AM - 10:00 AM","10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","1:00 PM - 2:30 PM","2:30 PM - 4:00 PM"],
            "Thursday": ["2:30 PM - 4:00 PM"],
            "Friday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM"],
            "Saturday": ["10:00 AM - 11:30 AM","2:30 PM - 4:00 PM","7:00 PM - 8:30 PM"],
            "Sunday": ["10:00 AM - 11:30 AM"]
        },
        "402": {
            "Monday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM","4:00 PM - 5:30 PM"],
            "Tuesday": ["1:00 PM - 2:30 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Wednesday": ["10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","4:00 PM - 5:30 PM"],
            "Thursday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM","1:00 PM - 2:30 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Friday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM","4:00 PM - 5:30 PM"],
            "Saturday": ["1:00 PM - 2:30 PM"],
            "Sunday": ["10:00 AM - 11:30 AM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"]
        },
        "501": {
            "Monday": ["11:30 AM - 1:00 PM"],
            "Tuesday": ["1:00 PM - 2:30 PM","2:30 PM - 4:00 PM"],
            "Wednesday": ["10:00 AM - 11:30 AM"],
            "Thursday": ["11:30 AM - 1:00 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Friday": ["1:00 PM - 2:30 PM","2:30 PM - 4:00 PM"],
            "Saturday": ["7:00 AM - 8:30 AM","11:30 AM - 1:00 PM","7:00 PM - 8:30 PM"],
            "Sunday": ["7:00 AM - 8:30 AM","8:30 AM - 10:00 AM","10:00 AM - 11:30 AM","1:00 PM - 2:30 PM","2:30 PM - 4:00 PM","7:00 PM - 8:30 PM"]
        },
        "502": {
            "Monday": ["7:00 AM - 8:30 AM","10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Tuesday": ["11:30 AM - 1:00 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Wednesday": ["1:00 PM - 2:30 PM","2:30 PM - 4:00 PM"],
            "Thursday": ["7:00 AM - 8:30 AM","10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM"],
            "Friday": ["8:30 AM - 10:00 AM","10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","2:30 PM - 4:00 PM","4:00 PM - 5:30 PM","5:30 PM - 7:00 PM","7:00 PM - 8:30 PM"],
            "Saturday": ["7:00 PM - 8:30 PM"],
            "Sunday": ["7:00 AM - 8:30 AM","8:30 AM - 10:00 AM","10:00 AM - 11:30 AM","11:30 AM - 1:00 PM","4:00 PM - 5:30 PM","5:30 PM - 7:00 PM","7:00 PM - 8:30 PM"]
        },
    };

    // Open modal and track selected room
    roomCards.forEach(card => {
        card.addEventListener('click', function () {
            const availability = this.querySelector('.availability').textContent;
            if (availability !== 'Yes') {
                alert('Room is not available!');
                return;
            }
            selectedRoom = this.getAttribute('data-room');
            roomInput.value = selectedRoom;
            modal.style.display = 'block';
            document.getElementById('day').value = "";
            document.getElementById('purpose').value = "";
            document.getElementById('reservation_time').innerHTML = "";
            document.getElementById('timeSlotDiv').style.display = 'none';
        });
    });

    // Close modal
    function closeModal() {
        modal.style.display = 'none';
    }

    // Show time slots based on room and day
    function showTimeSlot() {
        const day = document.getElementById("day").value;
        const timeSlotDiv = document.getElementById("timeSlotDiv");
        const timeSelect = document.getElementById("reservation_time");

        timeSelect.innerHTML = "";

        if (day && selectedRoom && timeSlots[selectedRoom] && timeSlots[selectedRoom][day]) {
            const slots = timeSlots[selectedRoom][day];
            timeSlotDiv.style.display = "block";

            slots.forEach(slot => {
                const option = document.createElement("option");
                option.value = slot;
                option.textContent = slot;
                timeSelect.appendChild(option);
            });
        } else {
            timeSlotDiv.style.display = "none";
        }
    }
</script>
</body>
</html>

