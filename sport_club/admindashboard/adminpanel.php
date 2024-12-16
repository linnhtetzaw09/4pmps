<?php
session_start();

include('../backend/db_connection.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../home.php');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total events count
$query_total_events = "SELECT COUNT(*) as total_events FROM events";
$result_total_events = $conn->query($query_total_events);
$total_events = $result_total_events->fetch_assoc()['total_events'];

// Fetch total registrations count
$query_total_registrations = "SELECT COUNT(*) as total_registrations FROM registers";
$result_total_registrations = $conn->query($query_total_registrations);
$total_registrations = $result_total_registrations->fetch_assoc()['total_registrations'];

// Fetch total members count
$query_total_users = "SELECT COUNT(*) as total_users FROM users";
$result_total_users = $conn->query($query_total_users);
$total_users = $result_total_users->fetch_assoc()['total_users'];

// Fetch all events from the events table
$query_events = "SELECT id, event_name, date, time, location, activities, age_group, description FROM events";
$result_events = $conn->query($query_events);

if (!$result_events) {
    die("Error fetching events: " . $conn->error);
} 

// Fetch all users from the users table
$query_users = "SELECT id, name, email, preferred_sport, skill_level, is_admin FROM users";
$result_users = $conn->query($query_users);

if (!$result_users) {
    die("Error fetching users: " . $conn->error);
}

$noadmin_users = "SELECT id, name, email, preferred_sport, skill_level, is_admin FROM users WHERE is_admin = 0";
$login_users = $conn->query($noadmin_users);

// Fetch registrations from pending_registers and registers
$query_pending = "SELECT * FROM pending_registers";
$result_pending = $conn->query($query_pending);

$query_approved = "SELECT * FROM registers";
$result_approved = $conn->query($query_approved);

$pendingRegistrations = [];
$approvedRegistrations = [];

if ($result_pending->num_rows > 0) {
    while ($row = $result_pending->fetch_assoc()) {
        $pendingRegistrations[] = $row;
    }
}

if ($result_approved->num_rows > 0) {
    while ($row = $result_approved->fetch_assoc()) {
        $approvedRegistrations[] = $row;
    }
}

// Combine and sort the results
$allRegistrations = array_merge($pendingRegistrations, $approvedRegistrations);
usort($allRegistrations, function($a, $b) {
    return strtotime($b['updated_at']) - strtotime($a['updated_at']);
});

// Assign a new sequential ID based on the combined and sorted registrations
foreach ($allRegistrations as $key => $register) {
    $allRegistrations[$key]['new_id'] = $key + 1;
}


?>

<?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
<?php endif; ?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>

        *{
            list-style: none;
            text-decoration: none !important;
        }

        .navbar, .footer{
            z-index: 1001;
        }

.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background-color: #042331;
    transition: all 0.5s ease;
    z-index: 1000; 
    overflow-y: auto;
}

.sidebar header {
    font-size: 22px;
    color: white;
    text-align: center;
    margin-top: 0;
    line-height: 70px;
    background: #063146;
    user-select: none;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid black;
}

.sidebar ul li a {
    display: block;
    height: 65px;
    line-height: 65px;
    padding-left: 15px;
    font-size: 18px;
    color: white;
    text-decoration: none;
    transition: padding-left 0.3s ease;
}

.sidebar ul li:hover a {
    padding-left: 30px;
    background-color: #063146;
}

.sidebar ul li a i {
    margin-right: 10px;
}

/* Section styles */
.main-content {
    margin-left: 250px; /* Adjust content next to the sidebar */
    padding: 20px;
    width: calc(100% - 250px); /* Dynamic content width */
}

.section {
    display: none; /* Hide all sections by default */
}

.section.active {
    display: block; /* Show the active section */
}


        .tbody {
            display: table-row-group;
            height: 100%; 
        }

        .tbody tr {
            height: 60px; 
            display: table-row; 
            vertical-align: middle;
        }

        #dashboard{
            padding-left: 200px;
            padding-right: -100px !important;
        }

        .btn-actions {
            width: 80px;
            height: 35px; 
            line-height: 1.5; 
            text-align: center;
            font-size: 14px; 
        }

        #registrationsChart {
            max-width: 100%; /* Ensure the chart takes up full width */
            height: auto; /* Maintain aspect ratio */
            max-height: 400px; /* Set a maximum height to avoid large sizes on smaller screens */
        }



    </style>
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
                <li class="nav-item"><a href="../home.php" class="nav-link active">Home</a></li>
                <li class="nav-item"><a href="../eventsDetails.php" class="nav-link">Event Details</a></li>
                <li class="nav-item"><a href="../news.php" class="nav-link">News & Announcements</a></li>
                <li class="nav-item"><a href="../about_us.php" class="nav-link">About Us</a></li>
                <li class="nav-item"><a href="../contact_us.php" class="nav-link">Contact Us</a></li>
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                    <li class="nav-item"><a href="adminpanel.php" class="nav-link">Dashboard</a></li>
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
                            <li><a href="../editProfile.php" class="dropdown-item">Edit Profile</a></li>
                            <li><a href="../logout.php" class="dropdown-item">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                   <li class="nav-item">
                        <a href="../login.php" class="nav-link">
                            <i class="bi bi-person-circle align-icon" style="font-size: 1.5rem;"></i> Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="sidebar">
    <header>Admin Control</header>
    <ul>
        <li><a href="javascript:void(0)" class="side-link" data-target="admin"><i class="fas fa-users"></i>Admin Table</a></li>
        <li><a href="javascript:void(0)" class="side-link" data-target="events"><i class="fas fa-table"></i>Events Table</a></li>
        <li><a href="javascript:void(0)" class="side-link" data-target="users"><i class="fas fa-user"></i>Users Table</a></li>
        <li><a href="javascript:void(0)" class="side-link" data-target="registration"><i class="fas fa-clipboard-list"></i>Registration Table</a></li>
        <li><a href="javascript:void(0)" class="side-link" data-target="analysis"><i class="fas fa-chart-bar"></i>Analysis</a></li>
    </ul>
</div>

    <!-- Dashboard Section -->
<section class="py-3">

        <div id="dashboard" class="container">

            <h2 class="text-center mb-4">Admin Dashboard</h2>
            
            <!-- Analytics Cards -->
            <div class="row g-4 mb-4 justify-content-center">
                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-info text-white text-center">
                        <div class="card-body">
                            <h4>Total Events</h4>
                            <p><?php echo $total_events; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-success text-white text-center">
                        <div class="card-body">
                            <h4>Total Registrations</h4>
                            <p><?php echo $total_registrations; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card bg-warning text-dark text-center">
                        <div class="card-body">
                            <h4> Total Members</h4>
                            <p><?php echo $total_users; ?></p>
                        </div>
                    </div>
                </div>
            </div>  
            
            <!-- Admin Table -->
            <div id="admin" class="card mb-4 shadow section active">
                <div class="card-header bg-warning text-dark text-center">
                    <h5>All Admins</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Email</th>
                                    <th>Preferred Sport</th>
                                    <th>Skill Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                <?php if ($result_users->num_rows > 0): ?>
                                    <?php $counter = 1; ?>
                                    <?php while ($user = $result_users->fetch_assoc()): ?>
                                        <?php if ($user['is_admin'] == 1):  ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['preferred_sport']); ?></td>
                                                <td><?php echo htmlspecialchars($user['skill_level']); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-primary view-btn" 
                                                        data-bs-toggle="modal" data-bs-target="#viewMemberModal" data-id="<?= $user['id']; ?>">
                                                        <i class="bi bi-eye"></i> View
                                                    </button>
                                                    <button class="btn btn-sm btn-danger remove-btn" data-id="<?= $user['id']; ?>">
                                                        <i class="bi bi-trash"></i> Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No members found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Manage Events -->
            <div id="events" class="card mb-4 shadow section">
                <div class="card-header bg-warning text-dark text-center">
                    <h5>Manage Events</h5>
                </div>
                <div class="card-body">
                    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-circle"></i> Add New Event
                    </button>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <!-- <th>Activities</th> -->
                                    <th>Age Group</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                <?php if ($result_events->num_rows > 0): ?>
                                    <?php while ($event = $result_events->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['id']); ?></td>
                                            <td><?php echo htmlspecialchars($event['event_name']); ?></td>
                                            <td><?php echo htmlspecialchars($event['date']); ?></td>
                                            <td>
                                                <?php
                                                $time = new DateTime($event['time']);
                                                echo $time->format('h:i A'); 
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                            <td><?php echo htmlspecialchars($event['age_group']); ?></td>
                                            <td><?php echo htmlspecialchars($event['description']); ?></td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <button class="btn btn-sm btn-primary btn-actions edit-btn" data-bs-toggle="modal" data-bs-target="#editEventModal" data-id="<?= $event['id']; ?>">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-danger btn-actions delete-btn" data-id="<?= $event['id']; ?>">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="9" class="text-center">No events found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Manage Members -->
            <div id="users" class="card mb-4 shadow section">
                <div class="card-header bg-warning text-dark text-center">
                    <h5>Manage Members</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Member Name</th>
                                    <th>Email</th>
                                    <th>Preferred Sport</th>
                                    <th>Skill Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($login_users->num_rows > 0): ?>
                                    <?php $counter = 1; ?>
                                    <?php while ($user = $login_users->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo htmlspecialchars($user['preferred_sport']); ?></td>
                                            <td><?php echo htmlspecialchars($user['skill_level']); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-primary view-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#viewMemberModal" data-id="<?= $user['id']; ?>">
                                                    <i class="bi bi-eye"></i> View
                                                </button>
                                                <button class="btn btn-sm btn-danger remove-btn" data-id="<?= $user['id']; ?>">
                                                    <i class="bi bi-trash"></i> Remove
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No members found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- Manage registration Section -->
            <div id="registration" class="card mt-4 shadow section">
                <div class="card-header bg-warning text-dark text-center">
                    <h5>Approved/Rejected Registrations</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Event Id</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($allRegistrations) > 0): ?>
                                    <?php foreach ($allRegistrations as $register): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($register['new_id']); ?></td>
                                            <td><?= htmlspecialchars($register['name']); ?></td>
                                            <td><?= htmlspecialchars($register['phone']); ?></td>
                                            <td><?= htmlspecialchars($register['event_id']); ?></td>
                                            <td>
                                                <?php 
                                             $dateTime = new DateTime($register['updated_at']);
                                                echo $dateTime->format('d M h:i A');
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($register['status'] == 'approved'): ?>
                                                    <span class="text-success">Approved</span>
                                                <?php else: ?>
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <button class="btn btn-sm btn-primary btn-approve" data-id="<?= $register['id']; ?>">Approve</button>
                                                        <button class="btn btn-sm btn-danger btn-reject" data-id="<?= $register['id']; ?>">Reject</button>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No registrations found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
            </div>

        </div>
</section>

            <div id="analysis" class="container mb-5 section">
                <h1 class="text-center mb-3">Text U want to give title </h1>
                <canvas id="registrationsChart"></canvas>
            </div>

  <!--footer -->
  <footer class="footer p-5 bg-dark text-white text-center position-relative">
    <div class="container">
        <p class="lead">Copyright &copy; 2024 GoSports. All Rights Reserved.</p>
        <a href="#" class="position-absolute bottom-0 end-0 p-5 text-warning">
            <i class="bi bi-arrow-up-circle h1 "></i>
        </a>
    </div>
 </footer>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_event.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="eventName" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="eventName" name="eventName" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="eventTime" name="eventTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="eventLocation" name="eventLocation" required>
                        </div>
                        <div class="mb-3">
                            <label for="activities" class="form-label">Activities</label>
                            <input type="text" class="form-control" id="activities" name="activities" required>
                        </div>
                        <div class="mb-3">
                            <label for="ageGroup" class="form-label">Age Group</label>
                            <input type="text" class="form-control" id="ageGroup" name="ageGroup" required>
                        </div>
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" name="eventDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="edit_event.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="editEventId" name="editEventId">
                        <div class="mb-3">
                            <label for="editEventName" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="editEventName" name="editEventName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editEventDate" name="editEventDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="editEventTime" name="editEventTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventLocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="editEventLocation" name="editEventLocation" required>
                        </div>
                        <div class="mb-3">
                            <label for="editActivities" class="form-label">Activities</label>
                            <input type="text" class="form-control" id="editActivities" name="editActivities" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAgeGroup" class="form-label">Age Group</label>
                            <input type="text" class="form-control" id="editAgeGroup" name="editAgeGroup" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEventDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editEventDescription" name="editEventDescription" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add News Modal -->
<div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addNewsModalLabel">Add News</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="newsTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="newsTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="newsDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="newsDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="newsContent" class="form-label">Content</label>
                        <textarea class="form-control" id="newsContent" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="eventPhoto" class="form-label">Event Photo</label>
                        <input type="file" class="form-control" id="eventPhoto" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add News</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Member Modal -->
<div class="modal fade" id="viewMemberModal" tabindex="-1" aria-labelledby="viewMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h5 class="modal-title" id="viewMemberModalLabel">Member Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Modal Body -->
        <div class="modal-body">
          <ul class="list-group">
            <li class="list-group-item"><strong>Name:</strong> <span id="modal-name"></span></li>
            <li class="list-group-item"><strong>Email:</strong> <span id="modal-email"></span></li>
            <li class="list-group-item"><strong>Signup Time: </strong> <span id="modal-signup-time"></span></li>
            <li class="list-group-item"><strong>Preferred Sports:</strong> <span id="modal-preferred-sport"></span></li>
            <li class="list-group-item"><strong>Skill Level:</strong> <span id="modal-skill-level"></span></li>
          </ul>
        </div>
        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
  

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
     integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


      <script>

document.addEventListener('DOMContentLoaded', function() {
    // Get all side-links
    const navLinks = document.querySelectorAll('.side-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior
            
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });

            const target = this.dataset.target;
            document.getElementById(target).classList.add('active');
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    // Select all edit buttons
    const editButtons = document.querySelectorAll('.edit-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const eventId = this.getAttribute('data-id');

            // Use AJAX to fetch event data from the server
            fetch(`get_event.php?id=${eventId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editEventId').value = data.id;
                    document.getElementById('editEventName').value = data.event_name;
                    document.getElementById('editEventDate').value = data.date;
                    document.getElementById('editEventTime').value = data.time;
                    document.getElementById('editEventLocation').value = data.location;
                    document.getElementById('editActivities').value = data.activities;
                    document.getElementById('editAgeGroup').value = data.age_group;
                    document.getElementById('editEventDescription').value = data.description;
                })
                .catch(error => console.error('Error fetching event data:', error));
        });
    });
});

$(document).on('click', '.delete-btn', function() {
    var eventId = $(this).data('id'); 
    
    if (confirm('Are you sure you want to delete this event?')) {
        $.ajax({
            url: 'delete_event.php',
            method: 'POST',
            data: { event_id: eventId },
            success: function(response) {
                response = JSON.parse(response);  // Parse the JSON response
                if (response.success) {
                    alert('Event deleted successfully.');
                    location.reload(); // Reload the page or update dynamically
                } else {
                    alert('Failed to delete the event. Error: ' + response.error);
                }
            },
            error: function() {
                alert('An error occurred while processing the request.');
            }
        });
    }
});

$(document).on('click', '.view-btn', function() {
    var userId = $(this).data('id');
    
    // Ajax request to fetch user data
    $.ajax({
        url: 'user_details.php',
        method: 'GET',
        data: { user_id: userId },
        success: function(response) {
            var user = JSON.parse(response); // Parse JSON response
            $('#modal-name').text(user.name);
            $('#modal-email').text(user.email);
            
            // Format Signup Time
            var signupTime = new Date(user.updated_at);
            var formattedDate = signupTime.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            });
            var formattedTime = signupTime.toLocaleTimeString('en-GB', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true // Display in 12-hour format
            });

            $('#modal-signup-time').text(formattedDate + ' ' + formattedTime);
            $('#modal-preferred-sport').text(user.preferred_sport);
            $('#modal-skill-level').text(user.skill_level);
        },
        error: function() {
            alert('Error fetching user details.');
        }
    });
});

$(document).on('click', '.remove-btn', function() {
    var userId = $(this).data('id'); // Get user ID from data-id attribute

    if (confirm('Are you sure you want to remove this user?')) {
        $.ajax({
            url: 'delete_user.php', // Your PHP script to handle deletion
            method: 'POST',
            data: { user_id: userId },
            success: function(response) {
                if (response.success) {
                    alert('User removed successfully.');
                    location.reload();
                } else {
                    location.reload();
                }
            },
            error: function() {
                alert('An error occurred while processing the request.');
            }
        });
    }
});

$(document).on('click', '.btn-approve', function () {
    const registrationId = $(this).data('id');

    $.ajax({
        url: 'admin_actions.php',
        type: 'POST',
        data: { id: registrationId, action: 'approve' },
        success: function (response) {
            if (response.success) {
                $(this).closest('tr').find('td:last-child').html('<span class="text-success">Approved</span>');
                alert('Registration approved successfully.');
            } else {
                alert('Approving Successful.');
                window.location.reload();
            }
        },
        error: function () {
            alert('An error occurred.');
        }
    });
});

$(document).on('click', '.btn-reject', function () {
    const registrationId = $(this).data('id');

    $.ajax({
        url: 'admin_actions.php',
        type: 'POST',
        data: { id: registrationId, action: 'reject' },
        success: function (response) {
            if (response.success) {
                $(this).closest('tr').remove();
                alert('Registration rejected successfully.');
            } else {
                alert('Rejected Successfully.');
                window.location.reload(); 
            }
        },
        error: function () {
            alert('An error occurred.');
        }
    });
});

// Utility function to generate random colors
function getRandomColor() {
    const letters = '0123456789ABCDEF';
    let color = '#';
    for (let i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// Your Chart.js code...
document.addEventListener('DOMContentLoaded', function () {
    fetch('chart.php')
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.event_name); // Event names
            const counts = data.map(item => item.registration_count); // Registration counts
            
            // Use random colors for the background of the bars
            const ctx = document.getElementById('registrationsChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels, // Event names
                    datasets: [{
                        label: 'Registrations',
                        data: counts,
                        backgroundColor: labels.map(() => getRandomColor()), // Random colors for bars
                        borderColor: 'rgba(0, 0, 0, 1)', 
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Error fetching chart data:', error));
});


      </script>

</body>
</html>
