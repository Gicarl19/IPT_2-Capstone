<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $day = trim($_POST["day"]);
    $time = trim($_POST["time"]);
    $purpose = trim($_POST["purpose"]);

    // Simple validation
    if (empty($name) || empty($day) || empty($time) || empty($purpose)) {
        echo "All fields are required.";
        exit;
    }

    // File to store reservations
    $file = 'room301_reservations.txt';

    // Check for duplicate reservation
    $existing = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
    foreach ($existing as $line) {
        list($resDay, $resTime, $resName) = explode('|', $line);
        if ($resDay === $day && $resTime === $time) {
            echo "⚠️ Sorry, the room is already reserved for <strong>$day</strong> at <strong>$time</strong>.";
            exit;
        }
    }

    // Save reservation
    $entry = "$day|$time|$name|$purpose" . PHP_EOL;
    file_put_contents($file, $entry, FILE_APPEND);

    echo "<h3>✅ Reservation Confirmed!</h3>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>Day:</strong> $day</p>";
    echo "<p><strong>Time:</strong> $time</p>";
    echo "<p><strong>Purpose:</strong> $purpose</p>";
    echo '<br><a href="/user_folder/user_page.php"><button>Back to Room</button></a>';
} else {
    echo "Invalid request.";
}
?>
