<?php
// Start session
session_start();

// Include database connection
include('backend/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user profile data from the database
$query = "SELECT name, email, preferred_sport, skill_level FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user's event history
$query_events = "SELECT e.event_name, e.date, e.time 
                 FROM events e 
                 JOIN registers r ON e.id = r.event_id
                 WHERE r.email = ? 
                 ORDER BY e.date DESC, e.time DESC";  
$stmt_events = $conn->prepare($query_events);
$stmt_events->bind_param("s", $user['email']);  // Use email to match user registrations
$stmt_events->execute();
$result_events = $stmt_events->get_result();

// Check if form is submitted to update profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);

  // Validate inputs
  if (!empty($name) && !empty($email)) {
      // Update user data
      $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
      $update_stmt = $conn->prepare($update_query);
      $update_stmt->bind_param("ssi", $name, $email, $user_id);

      if ($update_stmt->execute()) {
          // Success message with alert and redirect
          $_SESSION['name'] = $name; 
          echo "<script>
                  alert('Profile updated successfully!');
                  window.location.href = 'editProfile.php';
              </script>";
      } else {
          // Error message
          echo "<script>alert('Error updating profile: " . $update_stmt->error . "');</script>";
      }
  } else {
      echo "<script>alert('Please fill in all fields.');</script>";
  }
}

// Check if form is submitted to update preferences
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $preferred_sport = $_POST['preferred_sport'];
    $skill_level = $_POST['skill_level'];

    // Update user preferences
    $update_query = "UPDATE users SET preferred_sport = ?, skill_level = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $preferred_sport, $skill_level, $user_id);

    if ($update_stmt->execute()) {
        // Success message
        echo "<script>
                alert('Preferences updated successfully!');
                window.location.href = 'editProfile.php';
              </script>";
    } else {
        // Error message
        echo "Error updating preferences: " . $update_stmt->error;
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

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
  <!-- Profile Header -->
  <header class="py-5 bg-light text-center">
    <h1>Welcome, <span class="text-warning"><?php echo $user['name']; ?></span></h1>
    <p class="lead text-dark">Manage your profile and track your activity with <span class="text-warning">GoSports</span>.</p>
  </header>

<!-- User and Event History Section -->
<section class="profile-info py-5">
  <div class="container">
    <div class="row g-4">
      <!-- User Information Section -->
      <div class="col-lg-4 col-md-6">
        <div class="card shadow border-0">
          <div class="card-body text-center">
            <i class="bi bi-person-circle display-1 text-dark mb-3"></i>
            <h3><?php echo $user['name']; ?></h3>
            <p class="text-muted"><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
          </div>
        </div>
      </div>

      <!-- Event History Section -->
      <div class="col-lg-8 col-md-6">
        <div class="card shadow border-0">
          <div class="card-body">
            <h3 class="mb-4">Event History</h3>
            <ul class="list-group list-group-flush">
              <?php while ($event = $result_events->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center flex-column flex-sm-row">
                  <div class="w-100">
                    <strong><?php echo $event['event_name']; ?></strong> - <?php echo $event['date']; ?>
                  </div>
                  <div class="w-100 mt-2 mt-sm-0 d-flex justify-content-end align-items-center">
                    <?php if (strtotime($event['date']) > time()): ?>
                      <span class="badge bg-success">Upcoming</span>
                      <button class="btn btn-outline-primary btn-sm ms-2">Unregister</button>
                    <?php else: ?>
                      <span class="badge bg-warning">Completed</span>
                      <button class="btn btn-outline-danger btn-sm ms-2">Finished</button>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- Preferences Section -->
<section class="preferences py-5 bg-light">
    <div class="container">
        <h3 class="text-center mb-4">Preferences</h3>
        <form id="preferred" method="POST">
            <div class="mb-3">
                <label for="preferred_sport" class="form-label">Preferred Sports</label>
                <select class="form-select" id="preferred_sport" name="preferred_sport">
                    <option value="" disabled selected>Choose your preferred sport</option>
                    <option value="Football" <?php if ($user['preferred_sport'] == 'Football') echo 'selected'; ?>>Football</option>
                    <option value="Tennis" <?php if ($user['preferred_sport'] == 'Tennis') echo 'selected'; ?>>Tennis</option>
                    <option value="Swimming" <?php if ($user['preferred_sport'] == 'Swimming') echo 'selected'; ?>>Swimming</option>
                    <option value="Cycling" <?php if ($user['preferred_sport'] == 'Cycling') echo 'selected'; ?>>Cycling</option>
                    <option value="Basketball" <?php if ($user['preferred_sport'] == 'Basketball') echo 'selected'; ?>>Basketball</option>
                    <option value="Hiking" <?php if ($user['preferred_sport'] == 'Hiking') echo 'selected'; ?>>Hiking</option>
                    <option value="PingPong" <?php if ($user['preferred_sport'] == 'PingPong') echo 'selected'; ?>>Ping Pong</option>
                    <option value="Marathon" <?php if ($user['preferred_sport'] == 'Marathon') echo 'selected'; ?>>Marathon</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="skill_level" class="form-label">Skill Level</label>
                <select class="form-select" id="skill_level" name="skill_level">
                    <option value="" disabled selected>Choose your skill level</option>
                    <option value="Beginner" <?php if ($user['skill_level'] == 'Beginner') echo 'selected'; ?>>Beginner</option>
                    <option value="Intermediate" <?php if ($user['skill_level'] == 'Intermediate') echo 'selected'; ?>>Intermediate</option>
                    <option value="Advanced" <?php if ($user['skill_level'] == 'Advanced') echo 'selected'; ?>>Advanced</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Save Preferences</button>
        </form>
    </div>
</section>

  <!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form id="edit" method="POST" action="editProfile.php">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name</label>
                    <input type="text" class="form-control" id="editName" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Enter your new name">
                </div>
                <div class="mb-3">
                    <label for="editEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="Enter your email">
                </div>
                <button type="submit" class="btn btn-warning">Update Profile</button>
            </form>
            </div>
        </div>
    </div>
</div>


 <!--footer -->
 <footer class="p-5 bg-dark text-white text-center position-relative">
    <div class="container">
        <p class="lead">Copyright &copy; 2024 GoSports. All Rights Reserved.</p>
        <a href="#" class="position-absolute bottom-0 end-0 p-5 text-warning">
            <i class="bi bi-arrow-up-circle h1 "></i>
        </a>
    </div>
 </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
