$(document).ready(function () {
    console.log('Signup script initialized'); // Confirm script is initialized

    $('#signUpForm').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission
        console.log('Form submitted'); // Confirm form submission is detected

        // Send AJAX request
        $.ajax({
            url: './signup.php', // Update this path to match your PHP file
            type: 'POST',
            data: $(this).serialize(), // Serialize form data for the request
            dataType: 'json',
            success: function (response) {
                console.log('AJAX Success:', response); // Log success response
                if (response.success) {
                    alert(response.message); // Show success message
                    window.location.href = response.redirect; // Redirect to login
                } else {
                    $('#responseMessage').text(response.message); // Show error message
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown); // Log errors
                $('#responseMessage').text('An error occurred while processing the request.');
            }
        });
    });
});
