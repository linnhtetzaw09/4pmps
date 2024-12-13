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
            <form id="signUpForm" method="POST" action="signupaction.php">
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
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>
                <!-- Terms and Conditions -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms">I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a></label>
                </div>
                <!-- Sign Up Button -->
                <button type="submit" class="btn btn-dark w-100 mb-3">Sign Up</button>
            </form>
            
            <?php if (isset($error)) { echo "<p class='text-danger'>$error</p>"; } ?>
            
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

    <script>
        $(document).on('submit', '#signUpForm', function(e) {
            e.preventDefault(); // Prevent default form submission

            $.ajax({
                url: 'signupaction.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    var res = JSON.parse(response); 
                    if (res.success) {
                        window.location.href = 'login.php'; 
                    } else {
                        alert('Failed to sign up: ' + res.error); 
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', xhr, status, error); 
                    alert('An error occurred. Please try again.');
                }
            });
        });
    </script>
</body>
</html>
