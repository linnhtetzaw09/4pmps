<?php
session_start();

// // Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>
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

  <!-- Header Section -->
  <header class="py-5 bg-light text-center">
    <h1>Contact <span class="text-warning">GoSports</span></h1>
    <p class="lead text-dark">We’re here to help you with any inquiries or collaborations.</p>
  </header>

  <!-- Contact Information -->
  <section class="contact-info py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-6">
          <h3>Contact Details</h3>
          <ul class="list-group">
            <li class="list-group-item border-0">
              <i class="bi bi-geo-alt-fill text-warning"></i> <strong>Address:</strong> 123 Sports Avenue, Fitness City, Country
            </li>
            <li class="list-group-item border-0">
              <i class="bi bi-telephone-fill text-warning"></i> <strong>Phone:</strong> +1 234 567 890
            </li>
            <li class="list-group-item border-0">
              <i class="bi bi-envelope-fill text-warning"></i> <strong>Email:</strong> contact@gosports.com
            </li>
          </ul>
        </div>
        <div class="col-lg-6">
          <h3>Our Location</h3>
          <div class="ratio ratio-16x9 border rounded">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3818.5733964959683!2d96.08910301520104!3d21.975140985603926!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30cb720fe3ae8e41%3A0xa0f1d7e2cd12f1f6!2sMandalay%2C%20Myanmar%20(Burma)!5e0!3m2!1sen!2sus!4v1634246847763!5m2!1sen!2sus"
              width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </section>



  <!-- Social Media Links -->
  <section class="social-links py-4 text-center bg-dark text-white">
    <h4>Follow Us</h4>
    <a href="#" class="text-warning me-3"><i class="bi bi-facebook"></i></a>
    <a href="#" class="text-warning me-3"><i class="bi bi-twitter"></i></a>
    <a href="#" class="text-warning me-3"><i class="bi bi-instagram"></i></a>
    <a href="#" class="text-warning"><i class="bi bi-linkedin"></i></a>
  </section>

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
