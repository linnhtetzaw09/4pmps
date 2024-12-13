<?php
session_start();

include('backend/db_connection.php');

// Check if user is logged in
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
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
      rel="stylesheet" 
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
      crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">

</head>
<body class="bg-dark">

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
  
<!-- Showcase -->
<section class="bg-dark text-light p-5 p-lg-0 pt-lg-5 text-center text-sm-start">
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between">
            <div>
                <h1>Welcome to <span class="text-warning">Our Sports Club</span></h1>
                <p class="lead my-4">
                    Join us to experience exciting events, competitions,
                    and training sessions across various sports.
                    Connect with like-minded athletes and enhance your skills.
                </p>
                <a href="login.php" class="btn btn-warning btn-lg btn-jointheclub mb-3">Join the Club</a>
            </div>
            <img class="img-fluid w-50 d-none d-sm-block img-fluid rounded" src="img/sportclub.jpg" alt="Sports Club Image">
        </div>
    </div>
</section>


<!-- Filter Section -->
<section class="py-4">
    <div class="container bg-warning rounded shadow">
        <h3 class="text-center text-dark mb-4">Filter Events</h3>
        <div class="row justify-content-center">
            <!-- Filter Options -->
            <div class="col-md-10">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <!-- Filter Dropdowns -->
                    <div class="me-2 flex-grow-1">
                    <div class="mb-3 me-2 flex-grow-1">
                    <input type="date" class="form-control" id="dateFilter" placeholder="Select Date">
                    </div>
                    </div>
                    <div class="mb-3 me-2 flex-grow-1">
                        <select class="form-select" id="sportFilter">
                            <option value="">Select Sport</option>
                            <option value="Basketball">Basketball</option>
                            <option value="Football">Football</option>
                            <option value="Volleyball">Volleyball</option>
                            <!-- Add more sport options -->
                        </select>
                    </div>
                    <div class="mb-3 me-2 flex-grow-1">
                        <select class="form-select" id="locationFilter">
                            <option value="">Select Location</option>
                            <option value="Mandalay Stadium">Mandalay Stadium</option>
                            <option value="Central Sports Arena">Central Sports Arena</option>
                            <option value="Sunshine Beach Court">Sunshine Beach Court</option>
                            <!-- Add more location options -->
                        </select>
                    </div>
                    <div class="mb-3 flex-grow-1">
                        <select class="form-select" id="ageGroupFilter">
                            <option value="">Select Age Group</option>
                            <option value="Under 18">Under 18</option>
                            <option value="18-35">18-35</option>
                            <option value="36 and above">36 and above</option>
                            <!-- Add more age group options -->
                        </select>
                    </div>
                    <div class="mb-3 ms-2">
                        <button class="btn btn-dark px-4" type="button" id="filterBtn">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- upcoming and previous events -->
<section id="UpcomingEvents" class="py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Event 1 -->
            <div class="col-md-4">
                <div class="card shadow border-0 bg-secondary">
                    <img src="./img/table_tennis.jpg" class="card-img-top" alt="Event 1">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Mandalay Ping Pong Championship</h5>
                        <p class="card-text">Join us on December 15 at Mandalay Stadium for an exciting day of Ping Pong action!</p>
                        <a href="events-detail.php" class="btn btn-dark btn-sm btn-read-more">Read More</a>
                    </div>
                </div>
            </div>
            <!-- Event 2 -->
            <div class="col-md-4">
                <div class="card shadow border-0 bg-secondary">
                    <img src="./img/basketball.jpg" class="card-img-top" alt="Event 2">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Basketball Challenge</h5>
                        <p class="card-text">Don't miss the annual Basketball Challenge on January 10 at Sunshine Beach Court.</p>
                        <a href="events-detail.php" class="btn btn-dark btn-sm btn-read-more">Read More</a>
                    </div>
                </div>
            </div>
            <!-- Event 3 -->
            <div class="col-md-4">
                <div class="card shadow border-0 bg-secondary">
                    <img src="./img/hiking.jpg" class="card-img-top" alt="Event 3">
                    <div class="card-body">
                        <h5 class="card-title text-warning">Hiking</h5>
                        <p class="card-text">Experience the thrill of the Basketball Showdown on February 20 at Central Sports Arena.</p>
                        <a href="events-detail.php" class="btn btn-dark btn-sm btn-read-more">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- News & Announcements -->
<section id="News & Announcements" class="p-5 bg-light text-dark">
    <div class="container">
        <h2 class="text-center mb-5">News & Announcements</h2>
        <div class="row g-4">
            <!-- News Item 1 -->
            <div class="col-md-6">
                <div class="card bg-white shadow border-0" style="max-width: 500px; height: auto; margin: auto;">
                    <img src="./img/sport events.jpg" class="card-img-top" alt="Annual Sports Meet Announcement">
                    <div class="card-body">
                        <h5 class="card-title">Sports Events!</h5>
                        <p class="card-text text-dark">We are thrilled to announce that registrations are now open for all upcoming sports events
                            in Mandalay from January to J...</p>
                        <a href="news.php" class="btn btn-dark btn-sm  btn-read-more">Read More</a>
                    </div>
                </div>
            </div>

            <!-- News Item 2 -->
            <div class="col-md-6">
                <div class="card bg-white shadow border-0" style="max-width: 500px; height: auto; margin: auto;">
                    <img src="./img/pingpong_special_guest.jpg" class="card-img-top" alt="New Sports Announcement"
                    style="height: 315px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title">Table Tennis Champion</h5>
                        <p class="card-text text-dark"> We are thrilled to share that the Mandalay Ping Pong Championship will feature
                            a special appearance by Myanmar’s National Table Tennis...</p>
                        <a href="news.php" class="btn btn-dark btn-sm  btn-read-more">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mt-4">
            <!-- News Item 3 -->
            <div class="col-md-6">
                <div class="card bg-white shadow border-0" style="max-width: 500px; height: auto; margin: auto;">
                    <img src="./img/basketball_special_guest.jpg" class="card-img-top" alt="Championship Winners">
                    <div class="card-body">
                        <h5 class="card-title">Special Guest!</h5>
                        <p class="card-text text-dark">We are excited to announce that National Basketball
                             Coach Kyaw Zin Hlaing will be attending
                            the Mandalay City Basketball League on Feb..</p>
                        <a href="news.php" class="btn btn-dark btn-sm  btn-read-more">Read More</a>
                    </div>
                </div>
            </div>

            <!-- News Item 4 -->
            <div class="col-md-6">
                <div class="card bg-white shadow border-0" style="max-width: 500px; height: auto; margin: auto;">
                    <img src="./img/volunteer.jpg" class="card-img-top" alt="Volunteer Opportunities">
                    <div class="card-body">
                        <h5 class="card-title">Volunteer Needs!</h5>
                        <p class="card-text text-dark">Want to be part of the action? We're looking for volunteers to help at our sports events 
                            throughout the first half of 2025. Get involved in event mana..</p>
                        <a href="news.php" class="btn btn-dark btn-sm btn-read-more ">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- About Us-->
<section id="About Us" class="p-5 bg-dark text-light" >
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-md">
                <h2>About Us</h2>
                <p class="lead">
                    Our sports club is dedicated to promoting health, teamwork, 
                    and athletic skills across various sports. 
                    We organize regular training sessions, community events, and 
                    competitive tournaments.
                </p>
                <p>Become a part of our community, meet passionate athletes,
                     and push your limits in a supportive environment.
                </p>
                <a href="about_us.php" class="btn btn-light mt-3">
                    <i class="bi bi-chevron-right"></i> Read More
                </a>  
            </div>
            <div class="col-md"> 
                <img src="" class="img-fluid">
            </div>
        </div>
    </div>
</section>


   <!-- Contact-->
   <section class="text-warning bg-light text-center">
    <div class="container">

        <p class="text-dark">
            We're always looking for new partners, sponsors, and enthusiastic participants. Reach out to us for more
            information!
        </p>
        <a class="text-warning fw-bold" href="contact_us.php">Contact Us</a>
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

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-9ndCyUa1zO/3pWv+8s7m6aKMRrx9kybEcdt59mC26hpJ0e9e8fzp2Z9MtdlgQ9P8" 
        crossorigin="anonymous"></script>
      
</body>
</html>