
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reservation System</title>
    <link rel="icon" href="/images/cmulogo.png">
    <link rel="stylesheet" href="/style/reservation.css">
    <style>
        .unavailable {
            opacity: 0.5;
            background-color: #f44336;
        }

        .room-details {
            font-size: 1.1em;
        }

        .availability {
            font-weight: bold;
        }
    </style>
</head>
<body>
    s
    <div class="container">
        <h1>CBA ROOMS AVAILABLE</h1>
        <a href="admin_page.php">
            <button class="back-to-home">Back to Home</button>
        </a>
        <div class="room-selection" id="room-selection">
            <div class="room-card" data-room="Room 101">
                <h3>Room 101</h3>
                <p class="room-details">Capacity: 30</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 102">
                <h3>Room 102</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 103">
                <h3>Room 103</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 104">
                <h3>Room 104</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 201">
                <h3>Room 201</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 202">
                <h3>Room 202</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 203">
                <h3>Room 203</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 204">
                <h3>Room 204</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 301">
                <h3>Room 301</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 302">
                <h3>Room 302</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 303">
                <h3>Room 303</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
            <div class="room-card" data-room="Room 304">
                <h3>Room 304</h3>
                <p class="room-details">Capacity: 25</p>
                <p class="room-details">Available: <span class="availability">Yes</span></p>
            </div>
        </div>
    </div>

    <script>
        const roomCards = document.querySelectorAll('.room-card');

        // Update room availability based on the list of unavailable rooms
        roomCards.forEach(card => {
            const roomName = card.getAttribute('data-room');
            if (unavailableRooms.includes(roomName)) {
                card.classList.add('unavailable');
                card.querySelector('.availability').textContent = 'No'; // Change availability status
            }
        });
    </script>

</body>
</html>
