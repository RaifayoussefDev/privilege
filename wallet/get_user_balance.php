<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your password
$dbname = "privilage";


// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user_id parameter from request
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

if ($user_id > 0) {
    // Fetch user balance for the specified user
    $sql = "
    SELECT w.balance 
    FROM wallet w 
    WHERE w.user_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($balance);
    $stmt->fetch();
    $stmt->close();

    // Return the balance
    if ($balance !== null) {
        echo json_encode(['success' => true, 'balance' => $balance]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Balance not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
}

$conn->close();
?>