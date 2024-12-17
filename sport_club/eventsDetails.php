<?php
session_start();
include('backend/db_connection.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch events
$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$isLoggedIn = isset($_SESSION['user_id']); 
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
    rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
     crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-dark text-white">

  <!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3">
    <div class="container">
        <a href="home.php" class="navbar-brand text-warning fw-bold">GoSports</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a href="home.php" class="nav-link active">Home</a></li>
                <li class="nav-item"><a href="eventsDetails.php" class="nav-link">Event Details</a></li>
                <li class="nav-item"><a href="news.php" class="nav-link">News & Announcements</a></li>
                <li class="nav-item"><a href="about_us.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="contact_us.php" class="nav-link">Contact Us</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <li class="nav-item"><a href="./admindashboard/adminpanel.php" class="nav-link">Dashboard</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['name'])): ?>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="display: flex; align-items: center;">
                            <i class="bi bi-person-circle align-icon" style="font-size: 1.5rem;"></i>
                            <span style="margin-left: 10px;"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a href="editProfile.php" class="dropdown-item">Edit Profile</a></li>
                            <li><a href="logout.php" class="dropdown-item">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                   <li class="nav-item">
                        <a href="login.php" class="nav-link">
                            <i class="bi bi-person-circle align-icon" style="font-size: 1.5rem;"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

  <!-- Header -->
  <header class="event-header text-center bg-light text-dark py-5">
    <h1>Sport Events</h1>
    <p class="text-dark">Join us for a day of sports, fun, and excitement!</p>
  </header>

  <?php if (isset($_GET['success'])): ?>
    <script type="text/javascript">
        alert("<?php echo htmlspecialchars($_GET['success']); ?>");
    </script>
  <?php endif; ?>


<!-- Events Section -->
<?php if ($result->num_rows > 0): ?>
    <?php while ($event = $result->fetch_assoc()): ?>
        <section id="<?php echo htmlspecialchars($event['id']); ?>" 
            class="event-detail py-5 <?php echo $event['id'] % 2 === 0 ? 'bg-light' : ''; ?>" 
            data-title="<?php echo htmlspecialchars($event['event_name']); ?>" 
            data-date="<?php echo htmlspecialchars($event['date']); ?>" 
            data-time="<?php echo htmlspecialchars($event['time']); ?>" 
            data-location="<?php echo htmlspecialchars($event['location']); ?>" 
            data-age-group="<?php echo htmlspecialchars($event['age_group']); ?>">
            <div class="container">
                <h3 class="text-center <?php echo $event['id'] % 2 === 0 ? 'text-dark' : 'text-white'; ?> mb-4">
                    <?php echo htmlspecialchars($event['event_name']); ?>
                </h3>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                      <img src="<?php echo './uploadImages/' . htmlspecialchars($event['image_url']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($event['event_name']); ?>">
                    </div>
                    <div class="col-lg-6">
                        <h4 class="<?php echo $event['id'] % 2 === 0 ? 'text-dark' : 'text-white'; ?>">Event Details</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">📅 Date: <?php echo htmlspecialchars($event['date']); ?></li>
                            <li class="list-group-item">⏰ Time: <?php echo htmlspecialchars($event['time']); ?></li>
                            <li class="list-group-item">📍 Location: <?php echo htmlspecialchars($event['location']); ?></li>
                            <li class="list-group-item">🏆 Activities: <?php echo htmlspecialchars($event['activities']); ?></li>
                            <li class="list-group-item">👥 Age Group: <?php echo htmlspecialchars($event['age_group']); ?></li>
                        </ul>
                        <h4 class="mt-4 <?php echo $event['id'] % 2 === 0 ? 'text-dark' : 'text-white'; ?>">About the Event</h4>
                        <p class="<?php echo $event['id'] % 2 === 0 ? 'text-dark' : 'text-white'; ?>">
                            <?php echo htmlspecialchars($event['description']); ?>
                        </p>
                        <!-- Register Button -->
                        <button type="button" class="btn bg-warning text-dark mt-3 register-btn" data-event-id="<?php echo htmlspecialchars($event['id']); ?>" 
                                data-bs-toggle="modal" data-bs-target="#registerModal" 
                                data-title="<?php echo htmlspecialchars($event['event_name']); ?>">
                            Register
                        </button>
                    </div>
                </div>
            </div>
        </section>
    <?php endwhile; ?>
<?php else: ?>
    <div class="container text-center py-5">
        <h3 class="text-warning">No events available at the moment. Please check back later!</h3>
    </div>
<?php endif; ?>



<!-- Final Reminder Section -->
<section class="text-center py-5">
    <div class="container">
      <h3 class="text-warning">Don't Miss Out!</h3>
      <p>Remember, you can register for each event directly above. Sign up now and join the fun!</p>
    </div>
</section>


  <!-- Footer -->
  <footer class="p-5 bg-dark text-white text-center position-relative">
    <div class="container">
      <p class="lead">Copyright &copy; 2024 GoSports. All Rights Reserved.</p>
      <a href="#" class="position-absolute bottom-0 end-0 p-5 text-warning">
          <i class="bi bi-arrow-up-circle h1"></i>
      </a>
    </div>
  </footer>

<!-- Modal for Registration -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-dark" id="registerModalLabel">Register</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if ($isLoggedIn): ?>
          <form id="registerForm" method="POST" action="register.php">
            <!-- Hidden input for event_id -->
            <input type="hidden" name="event_id" id="event_id">

            <div class="mb-3">
              <label for="name" class="form-label text-dark">Full Name</label>
              <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your name">
            </div>
            <div class="mb-3">
              <label for="age" class="form-label text-dark">Age</label>
              <input type="number" class="form-control" id="age" name="age" required placeholder="Enter your age">
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label text-dark">Phone</label>
              <input type="text" class="form-control" id="phone" name="phone" required placeholder="Enter your phone">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label text-dark">Email Address</label>
              <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
            </div>
            <button type="submit" class="btn bg-warning text-dark w-100">Submit Registration</button>
          </form>
        <?php else: ?>
          <p class="text-center">You must be logged in to register.</p>
          <p class="text-center"><a href="login.php">Login here</a> to continue.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


<script>
    // Wait until the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', () => {
        // Select all buttons that open the modal
        const registerButtons = document.querySelectorAll('[data-bs-target="#registerModal"]');
        
        // Loop through the buttons and add event listeners
        registerButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Get the event ID and event title from the button attributes
                const eventId = button.getAttribute('data-event-id'); // Corrected attribute name
                const eventTitle = button.getAttribute('data-title');
                
                // Update the modal title with the event title
                const modalTitle = document.getElementById('registerModalLabel');
                modalTitle.textContent = `Register for ${eventTitle}`;
                
                // Set the hidden event_id input field in the form
                const eventIdInput = document.getElementById('event_id');
                eventIdInput.value = eventId;
            });
        });
    });
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
