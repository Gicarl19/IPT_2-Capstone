<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '1234';
$dbname = 'user_db';
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User login simulation (for demo purposes)
if (!isset($_SESSION['user_role'])) {
    $_SESSION['user_role'] = isset($_GET['admin']) ? 'admin' : 'user';
    $_SESSION['username'] = $_SESSION['user_role'] === 'admin' ? 'Admin' : 'User' . rand(100, 999);
}

$username = $_SESSION['username'];
$user_role = $_SESSION['user_role'];

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $conn->real_escape_string($_POST['message']);
    $receiver = ($user_role === 'admin') ? $_POST['receiver'] : 'Admin';
    
    $conn->query("INSERT INTO messages (sender, receiver, message) VALUES ('$username', '$receiver', '$message')");
}

// Fetch chat history
if ($user_role === 'admin') {
    // Admin sees messages from all users
    $messages = $conn->query("SELECT * FROM messages WHERE receiver='Admin' OR sender='Admin' ORDER BY message_id ASC");
} else {
    // Users only see their own messages with admin
    $messages = $conn->query("SELECT * FROM messages WHERE (sender='$username' AND receiver='Admin') OR (sender='Admin' AND receiver='$username') ORDER BY message_id ASC");
}

if (!$messages) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat System</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background-color: #f4f4f9; 
            margin: 0; 
            padding: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            position: relative;
        }
        #chat-container { 
            width: 400px; 
            border: 2px solid #333; 
            border-radius: 10px; 
            background-color: #fff; 
            padding: 20px; 
        }
        #chat-box { 
            height: 300px; 
            overflow-y: auto;
            border-bottom: 1px solid #ccc; 
            margin-bottom: 10px; 
            padding-bottom: 10px; 
        }
        .message { 
            margin-bottom: 10px; 
        }
        .username { 
            font-weight: bold; 
            color: #007bff; 
        }
        form { 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
        }
        input[type="text"], 
        select { padding: 5px; }
        .home-button {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #012362;
            padding: 10px;
            border-radius: 5px;
        }
        .home-button a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
    </style> 
    <link rel="icon" type="image/x-icon" href="/images/cmulogo.png">
</head>
<body>
    <div class="home-button">
        <a href="admin_page.php">Home</a>
    </div>
    <div id="chat-container">
        <h3>Chat with <?= $user_role === 'admin' ? 'Users' : 'Admin' ?></h3>
        <div id="chat-box">
            <?php while ($msg = $messages->fetch_assoc()) : ?>
                <div class="message">
                    <span class="username"><?php echo htmlspecialchars($msg['sender']); ?>:</span>
                    <span><?php echo htmlspecialchars($msg['message']); ?></span>
                </div>
            <?php endwhile; ?>
        </div>
        
        <form action="" method="post">
            <?php if ($user_role === 'admin') : ?>
                <select name="receiver" required>
                    <option value="role">Select User</option>
                    <?php
                    $users = $conn->query("SELECT DISTINCT sender FROM messages WHERE sender != 'Admin'");
                    while ($user = $users->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($user['sender']) . "'>" . htmlspecialchars($user['sender']) . "</option>";
                    }
                    ?>
                </select>
            <?php endif; ?>
            <input type="text" name="message" placeholder="Type your message..." required />
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>