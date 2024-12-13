<?php
session_start();
include('../backend/db_connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $action = $_POST['action'];

    try {
        if ($action === 'approve') {
            // Fetch the record from pending_registers
            $query = "SELECT * FROM pending_registers WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $registration = $result->fetch_assoc();

                // Insert the record into registers table
                $insertQuery = "INSERT INTO registers (name, age, phone, email, event_id, status) 
                                VALUES (?, ?, ?, ?, ?, 'approved')";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param(
                    "sisss",
                    $registration['name'],
                    $registration['age'],
                    $registration['phone'],
                    $registration['email'],
                    $registration['event_id']
                );

                if ($insertStmt->execute()) {
                    // Delete the record from pending_registers
                    $deleteQuery = "DELETE FROM pending_registers WHERE id = ?";
                    $deleteStmt = $conn->prepare($deleteQuery);
                    $deleteStmt->bind_param("i", $id);
                    $deleteStmt->execute();

                    echo json_encode(['success' => true]);
                } else {
                    throw new Exception('Failed to insert into registers');
                }

                $insertStmt->close();
            } else {
                throw new Exception('Record not found');
            }

            $stmt->close();
        } elseif ($action === 'reject') {
            // Delete the record from pending_registers
            $query = "DELETE FROM pending_registers WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to delete');
            }

            $stmt->close();
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $conn->close();
    }
}
?>
