<?php
// Include the database connection file
include('backend/db_connection.php'); // Corrected path

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // SQL query to insert new user into the database
    $sql = "INSERT INTO users (name, email, password, created_at, updated_at) 
            VALUES ('$name', '$email', '$password', NOW(), NOW())";

    // Execute query and handle the result
    if ($conn->query($sql) === TRUE) {
        // Redirect to home.php
        header('Location: home.php');
        exit(); // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./script/sign_up.js"></script> <!-- Custom JS file for additional functionality -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="max-width: 500px; width: 100%;">
            <h2 class="text-center mb-4">Sign Up</h2>
            <!-- Sign Up Form -->
            <form id="signUpForm" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password" required>
                </div>
                <!-- Terms and Conditions -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a></label>
                </div>
                <!-- Sign Up Button -->
                <button type="submit" class="btn btn-dark w-100 mb-3">Sign Up</button>
            </form>
            <hr>
            <!-- Already Have an Account -->
            <div class="text-center">
                <p class="mb-2">Already have an account?</p>
                <a href="login.php" class="btn btn-outline-dark w-100">Login</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
