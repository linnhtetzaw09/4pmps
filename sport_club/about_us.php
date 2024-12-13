<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
  <header class="news-header text-dark">
    <h1>About <span class="text-warning">GoSports</span></h1>
    <p class="text-dark">Inspiring Sportsmanship, Unity, and Excellence</p>
  </header>

    <!-- Mission Section -->
    <section class="mission-section py-5 bg-dark">
        <div class="container">
            <h3 class="text-center text-light mb-4"><span class="text-warning">Mission</span></h3>
            <p class="text-center">
                At GoSports, our mission is to promote a healthy lifestyle, foster sportsmanship, and create unforgettable
                experiences for athletes and fans alike. We believe sports have the power to bring people together and create
                lasting memories.
            </p>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="vision-section py-5 text-light bg-dark">
        <div class="container">
            <h3 class="text-center mb-4"><span class="text-warning">Vision</span></h3>
            <p class="text-center">
                We envision a world where sports unite communities, inspire individuals, and create opportunities for all to
                engage in physical activity. We aim to be the leading platform for sports events, training, and inspiration.
            </p>
        </div>
    </section>


     <!-- Core Values Section -->
     <section class="team-section py-5 bg-dark">
        <div class="container">
            <h3 class="text-center mb-5 text-warning">Core Values</h3>
            <div class="row  g-4">
                <!-- Integrity -->
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center border-0 shadow bg-warning">
                        <i class="bi bi-heart fs-1"></i>
                    <h5 class="text-dark">Integrity</h5>
                    <p class="text-dark">We believe in honesty, transparency, and doing what's right.</p>
                    </div>
                </div>
                <!--Innovation-->
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center border-0 shadow bg-warning">
                        <i class="bi bi-lightbulb fs-1"></i>
                    <h5>Innovation</h5>
                    <p class="text-dark">We embrace creativity and new ideas to solve problems.</p>
                    </div>
                </div>
                <!-- Collaboration -->
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center border-0 shadow bg-warning">
                        <i class="bi bi-people fs-1"></i>
                        <h5>Collaboration</h5>
                        <p class="text-dark">We work together to achieve our goals and support each other.</p>
                    </div>
                </div>
</section>

    <!-- Our Team Section -->
    <section class="team-section py-5">
        <div class="container">
            <h3 class="text-center mb-5">Meet Our <span class="text-warning">Team</span></h3>
            <div class="row g-4">
                <!-- Team Member 1 -->
                <div class="col-lg-3 col-md-6">
                <div class="card text-center border-0 shadow" style="max-width: 500px; height: 400px; margin: auto;">
                        <img src="img/boy.jpg" class="card-img-top rounded-top" alt="Alex Johnson">
                        <div class="card-body">
                            <h5 class="card-title">Alex Johnson</h5>
                            <br>
                            <p class="card-text text-dark">Event Coordinator</p>
                        </div>
                    </div>
                </div>
                <!-- Team Member 2 -->
                <div class="col-lg-3 col-md-6">
                <div class="card text-center border-0 shadow" style="max-width: 500px; height: 400px; margin: auto;">
                        <img src="img/girl.jpg" class="card-img-top rounded-top" alt="Maria Lopez">
                        <div class="card-body">
                            <h5 class="card-title">Maria Lopez</h5>
                            <br>
                            <p class="card-text text-dark">Marketing Manager</p>
                        </div>
                    </div>
                </div>
                <!-- Team Member 3 -->
                <div class="col-lg-3 col-md-6">
                <div class="card text-center border-0 shadow" style="max-width: 500px; height: 400px; margin: auto;">
                        <img src="img/boy (2).jpg" class="card-img-top rounded-top" alt="James Carter">
                        <div class="card-body">
                            <h5 class="card-title">James Carter</h5>
                            <br>
                            <p class="card-text text-dark">Sports Director</p>
                        </div>
                    </div>
                </div>
                <!-- Team Member 4 -->
                <div class="col-lg-3 col-md-6">
                    <div class="card text-center border-0 shadow" style="max-width: 500px; height: 400px; margin: auto;">
                    <img src="img/girl (2).jpg" class="card-img-top rounded-top" alt="Emma Davis">
                        <div class="card-body" >
                            <h5 class="card-title">Emma Davis</h5>
                            <br>
                            <p class="card-text text-dark">Fitness Trainer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="achievements py-5">
        <div class="container text-center">
            <h3>Our Achievements</h3>
            <div class="row">
                <div class="col-md-4">
                    <h4>100+</h4>
                    <p class="text-dark">Successful Projects</p>
                </div>
                <div class="col-md-4">
                    <h4>50+</h4>
                    <p class="text-dark">Team Members</p>
                </div>
                <div class="col-md-4">
                    <h4>10</h4>
                    <p class="text-dark">Awards Won</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="contact-cta bg-dark text-warning text-center">
        <div class="container">
            <h3>Want to Collaborate?</h3>
            <p>
                We're always looking for new partners, sponsors, and enthusiastic participants. Reach out to us for more
                information!
            </p>
            <a class="text-warning" href="contact_us.php">Contact Us</a>
        </div>
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
