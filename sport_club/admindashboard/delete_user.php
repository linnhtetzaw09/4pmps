<?php
session_start();
include('../backend/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID from POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);

    // Delete the user from the database
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        // Re-index remaining users' IDs
        $stmt = $conn->prepare("UPDATE users SET id = id - 1 WHERE id > ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $response = ['success' => true];
    } else {
        $response = ['success' => false, 'error' => $stmt->error];
    }
    $stmt->close();
    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
