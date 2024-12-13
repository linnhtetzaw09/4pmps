<?php

session_start();
include('backend/db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    if (empty($name) || empty($age) || empty($phone) || empty($email)) {
        header("Location: eventsDetails.php?event_id=" . $event_id . "&error=All fields are required.");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO pending_registers (name, age, phone, email, event_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $name, $age, $phone, $email, $event_id);

    if ($stmt->execute()) {
        header("Location: eventsDetails.php?event_id=" . $event_id . "&success=Registration Submitted for Approval!");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
