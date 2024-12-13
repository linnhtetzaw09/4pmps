<?php
session_start();
include('../backend/db_connection.php');

// Query to count registrations per event and fetch event names
$query = "
    SELECT events.event_name AS event_name, COUNT(registers.id) AS registration_count
    FROM registers
    INNER JOIN events ON registers.event_id = events.id
    GROUP BY events.id
";

$result = $conn->query($query);

// Check if any data is returned
if ($result->num_rows > 0) {
    $chartData = [];
    while ($row = $result->fetch_assoc()) {
        $chartData[] = [
            'event_name' => $row['event_name'],
            'registration_count' => $row['registration_count']
        ];
    }
    echo json_encode($chartData);
} else {
    echo json_encode([]); // No data found
}
