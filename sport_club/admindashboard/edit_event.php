<?php
session_start();
include('../backend/db_connection.php');

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $eventId = $_POST['editEventId'];
    $eventName = $_POST['editEventName'];
    $eventDate = $_POST['editEventDate'];
    $eventTime = $_POST['editEventTime'];
    $eventLocation = $_POST['editEventLocation'];
    $activities = $_POST['editActivities'];
    $ageGroup = $_POST['editAgeGroup'];
    $eventDescription = $_POST['editEventDescription'];

    // Handle file upload
    $target_dir = "../uploadImages/";
    $image_url = $_POST['existingImageUrl']; 
    
    if (!empty($_FILES["editImage"]["name"])) {
        $target_file = $target_dir . basename($_FILES["editImage"]["name"]);
        if (move_uploaded_file($_FILES["editImage"]["tmp_name"], $target_file)) {
            $image_url = basename($_FILES["editImage"]["name"]); // Save the new file name
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("UPDATE events SET 
        event_name = ?, 
        date = ?, 
        time = ?, 
        location = ?, 
        activities = ?, 
        age_group = ?, 
        description = ?, 
        image_url = ? 
        WHERE id = ?");
    $stmt->bind_param("ssssssssi", $eventName, $eventDate, $eventTime, $eventLocation, $activities, $ageGroup, $eventDescription, $image_url, $eventId);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: adminpanel.php");
        exit();
    } else {
        // Redirect back with error message
        header("Location: adminpanel.php?error=Failed to update the event.");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
