<?php
session_start();

require "../inc/conn_db.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Validate and sanitize input
    $session_planning_id = isset($_GET['session_IdSp']) ? intval($_GET['session_IdSp']) : null;
    $user_id = isset($_GET['user_ID']) ? intval($_GET['user_ID']) : null;

    // If user_id is not provided in the query string, use the one from the session
    if ($user_id === null) {
        if (isset($_SESSION['id'])) {
            $user_id = intval($_SESSION['id']);
        } else {
            die("error: User session not found or invalid.");
        }
    }

    if ($session_planning_id === null) {
        die("error: session_planning_id is missing or invalid.");
    }

    // Check if the user has already reserved this session schedule
    $check_reservation_query = "SELECT id FROM reservations WHERE user_id = ? AND session_planning_id = ?";
    $stmt_check = $conn->prepare($check_reservation_query);
    $stmt_check->bind_param("ii", $user_id, $session_planning_id);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        die("error: Vous avez déjà réservé cette session.");
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Validate user existence
        $user_check_query = "SELECT id FROM users WHERE id = ?";
        $stmt_user = $conn->prepare($user_check_query);
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $stmt_user->store_result();

        if ($stmt_user->num_rows === 0) {
            die("error: User does not exist or invalid user ID.");
        }

        // Prepare SQL statement to insert into reservations
        $stmt_reservation = $conn->prepare("INSERT INTO reservations (user_id, session_planning_id, date_reservation) VALUES (?, ?, NOW())");
        $stmt_reservation->bind_param("ii", $user_id, $session_planning_id);

        if (!$stmt_reservation->execute()) {
            die("error: Execute failed - " . htmlspecialchars($stmt_reservation->error));
        }

        // Update remaining_slots in session_planning
        $stmt_update = $conn->prepare("UPDATE session_planning SET remaining_slots = remaining_slots - 1 WHERE id = ? AND remaining_slots > 0");
        $stmt_update->bind_param("i", $session_planning_id);

        if (!$stmt_update->execute()) {
            die("error: Execute failed - " . htmlspecialchars($stmt_update->error));
        }

        // Check if any rows were affected
        if ($stmt_update->affected_rows === 0) {
            die("error: No slots available or invalid session planning ID.");
        }

        // Commit transaction if everything is successful
        $conn->commit();
        echo "success";
    } catch (Exception $e) {
        // Rollback the transaction if something failed
        $conn->rollback();
        die("error: " . $e->getMessage());
    }

    $stmt_check->close();
    $stmt_user->close();
    $stmt_reservation->close();
    $stmt_update->close();
    $conn->close();
}
?>
