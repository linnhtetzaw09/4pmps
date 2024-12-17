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

    // Handle file upload
    $target_dir = "../uploadImages/";
    $image_url = ''; // Default to empty if no image is uploaded
    
    // Check if file is uploaded and move to target directory
    if (isset($_FILES["image"]["name"]) && !empty($_FILES["image"]["name"])) {
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = basename($_FILES["image"]["name"]); // Save the file name
        } else {
            $_SESSION['error'] = "Error uploading the image.";
            header("Location: adminpanel.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Please upload an image.";
        header("Location: adminpanel.php");
        exit;
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO events (event_name, date, time, location, activities, age_group, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $event_name, $event_date, $event_time, $event_location, $event_activities, $event_age_group, $event_description, $image_url);

    if ($stmt->execute()) {
        // Redirect back to the manage events section with success message
        $_SESSION['success'] = "Event added successfully.";
        header("Location: adminpanel.php");
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: adminpanel.php");
    }

    $stmt->close();
    $conn->close();
}
?>
