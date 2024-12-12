<?php
include('backend/db_connection.php');

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data from the POST request
    $event_id = $_POST['event_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Validate the form data (basic validation)
    if (empty($name) || empty($age) || empty($phone) || empty($email)) {
        // Redirect with error message
        header("Location: events-details.php?event_id=" . $event_id . "&error=All fields are required.");
        exit;
    }

    // Prepare the SQL query to insert the registration data using prepared statements
    $stmt = $conn->prepare("INSERT INTO registers (name, age, phone, email, event_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $name, $age, $phone, $email, $event_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: eventsDetails.php?event_id=" . $event_id . "&success=Registration Successful!");
        exit;
    } else {
        echo "Error";
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
?>
