<?php
// Connect to the database
$conn = new mysqli('localhost', 'root', '1234', 'user_db');

// Check the connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = $_POST['report_id'];

    // Delete the report from the database
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->bind_param("i", $report_id);

    if ($stmt->execute()) {
        echo "Report deleted successfully.";
        header("Location: /ad_folder/admin_report.php"); // Redirect back to the admin page
        exit();
        echo "Error deleting the report: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
