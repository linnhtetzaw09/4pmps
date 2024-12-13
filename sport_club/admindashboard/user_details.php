<?php
session_start();
include('../backend/db_connection.php');

if (isset($_GET['user_id'])) {
    $userId = intval($_GET['user_id']);

    $stmt = $conn->prepare("SELECT name, email, updated_at, preferred_sport, skill_level FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        echo json_encode($user); // Send JSON response
    } else {
        echo json_encode(['error' => 'Error fetching user details']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
