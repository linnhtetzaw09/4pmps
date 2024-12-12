<?php
include('backend/db_connection.php');

// Start session to manage login state
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize the inputs to avoid SQL injection
    $email = mysqli_real_escape_string($conn, $email);

    // SQL query to check if the user exists in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error); // Debug database errors
    }

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            // Redirect to dashboard or homepage
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Login</h2>
            <!-- Login Form -->
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <input type="checkbox" id="rememberMe" name="rememberMe">
                        <label for="rememberMe" class="form-check-label">Remember me</label>
                    </div>
                    <a href="#" class="text-decoration-none"></a>
                </div>
                <!-- Login Button -->
                <button type="submit" class="btn btn-dark w-100 mb-3">Login</button>
            </form>

            <?php
            // Display error message if credentials are invalid
            if (isset($error_message)) {
                echo '<div class="alert alert-danger" role="alert">' . $error_message . '</div>';
            }
            ?>
            <hr>
            <!-- Sign Up Section -->
            <div class="text-center">
                <p class="mb-2">Don't have an account?</p>
                <!-- Sign Up Button -->
                <a href="signup.php" class="btn btn-outline-dark w-100">Sign Up</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
