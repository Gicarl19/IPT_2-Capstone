<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reservation System</title>
    <link rel="icon" href="/images/cmulogo.png">
    <link rel="stylesheet" href="reserve.css">
</head>
<body>
    <div class="container">
        <h1>CBA ROOMS AVAILABLE</h1>
        <a href="user_page.php">
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
            <!-- Add more room cards as needed -->
        </div>
    </div>

    <!-- The Modal -->
    <div id="reservation-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Reserve a Room</h2>
            <form id="reservation-form" action="process_reservations.php" method="POST">
                <input type="hidden" id="roomno" name="roomno">
                <input type="hidden" name="action" value="insert">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="yrandsection">Year and Section:</label>
                <input type="text" id="yrandsection" name="yrandsection" required>
                <button type="submit">Reserve Now</button>
                <div id="error-message" class="error"></div>
            </form>
        </div>
    </div>

    <script>
        const roomCards = document.querySelectorAll('.room-card');
        const modal = document.getElementById('reservation-modal');
        const roomInput = document.getElementById('roomno');
        let selectedRoom = '';

        roomCards.forEach(card => {
            card.addEventListener('click', function() {
                if (this.classList.contains('unavailable')) return;
                selectedRoom = this.getAttribute('data-room');
                roomInput.value = selectedRoom;
                modal.style.display = 'block';
            });
        });

        function closeModal() {
            modal.style.display = 'none';
        }
    </script>
</body>
</html>