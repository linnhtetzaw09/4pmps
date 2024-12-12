<?php

session_start();
include('../backend/db_connection.php');

if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Fetch the event data
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Event not found"]);
    }

    $stmt->close();
    $conn->close();
}
?>
