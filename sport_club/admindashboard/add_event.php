<?php
session_start();
include('../backend/db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_name = $_POST['eventName'];
    $event_date = $_POST['eventDate'];
    $event_time = $_POST['eventTime'];
    $event_location = $_POST['eventLocation'];
    $event_activities = $_POST['activities'];
    $event_age_group = $_POST['ageGroup']; 
    $event_description = $_POST['eventDescription'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO events (event_name, date, time, location, activities, age_group, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $event_name, $event_date, $event_time, $event_location, $event_activities, $event_age_group, $event_description);

    if ($stmt->execute()) {
        // Redirect back to the manage events section
        header("Location: adminpanel.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
