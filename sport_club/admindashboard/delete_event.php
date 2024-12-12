<?php
session_start();
include('../backend/db_connection.php');

// delete_event.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $eventId = intval($_POST['event_id']);

    // Delete the event from the database
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    if ($stmt->execute()) {
        // Optionally, re-index IDs
        $stmt = $conn->prepare("UPDATE events SET id = id - 1 WHERE id > ?");
        $stmt->bind_param("i", $eventId);
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
