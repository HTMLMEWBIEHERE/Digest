<?php
include 'components/connect.php';
include 'components/user_header.php';

$db = new Database();
$conn = $db->connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/landing_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="main-content">
    <div class="carousel-container">
        <div class="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../imgs/cover.jpg" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="../imgs/c2.jpg" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="../imgs/c3.jpg" alt="Slide 3">
                </div>
            </div>

            <button class="carousel-control prev" onclick="moveSlide(-1)">&#10094;</button>
            <button class="carousel-control next" onclick="moveSlide(1)">&#10095;</button>

            <div class="carousel-indicators">
                <span class="indicator active" onclick="goToSlide(0)"></span>
                <span class="indicator" onclick="goToSlide(1)"></span>
                <span class="indicator" onclick="goToSlide(2)"></span>
            </div>
        </div>

        <!-- Overlapping Card -->
        <div class="card-1">
            <h3>Purpose of University Digest</h3>
            <br>
            <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <br>
            <div class="icon-text">
                <i class="fas fa-school"></i> <span>Western Mindanao <br> State University</span>
                <i class="fas fa-map-marker-alt"></i> <span>WMSU <br> Campus A</span>
                <i class="fas fa-calendar-alt"></i> <span>EST. <br> MCMLXXVIII</span>
                <i class="fas fa-clock"></i> <span>Mon-Sat, <br> 8AM-5PM</span>
            </div>
        </div>
    </div>

    <!-- New Card -->
    <div class="card-2">
        <div class="card-content">
            <h1 style="color:#4F0003;">About the University Digest</h1>
            <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            <br>
            <a href="../content/about_us.php" class="read-more-button">More About Us</a>
        </div>
        <div class="card-image">
            <img src="../imgs/logo_trans.png" alt="Sample Image">
        </div>
    </div>

    <!-- Additional Card with Four Smaller Cards in Divider -->
    <div class="card-3-divider">
        <h2 class="articles-header">ARTICLES</h2>
        <div class="card-3">
            <div class="smaller-card">
                <i class="fas fa-newspaper"></i>
                <h4>News</h4>
                <p>Short description 1</p>
                <a href="../content/news.php" class="explore-button">Explore</a>
            </div>
            <div class="smaller-card">
                <i class="fas fa-book"></i> <!-- Changed to a free Font Awesome icon -->
                <h4>Magazines</h4>
                <p>Short description 2</p>
                <a href="../content/e_magazines.php" class="explore-button">Explore</a> <!-- Fixed link -->
            </div>
            <div class="smaller-card">
                <i class="fas fa-pencil-alt"></i>
                <h4>Editorial</h4>
                <p>Short description 3</p>
                <a href="../content/editorial.php" class="explore-button">Explore</a> <!-- Fixed link -->
            </div>
            <div class="smaller-card">
                <i class="fas fa-asterisk"></i>
                <h4>Miscellaneous</h4>
                <p>Short description 4</p>
                <a href="../content/misc.php" class="explore-button">Explore</a> <!-- Fixed link -->
            </div>
        </div>
    </div>

    <!-- Announcements Card -->
    <div class="card-announcements">
        <div class="card-content">
            <h1 style="color:#4F0003;">Announcements</h1>
            <p>"Stay updated with the latest announcements and news from the university. From important dates to upcoming events, find all the information you need right here."</p>
            <br>
            <a href="../content/announcements.php" class="read-more-button">View Announcements</a>
        </div>
        <!-- <div class="card-image">
            <img src="../imgs/announcement.png" alt="Announcements Image">
        </div> -->
    </div>

    <!-- Tejidos Card -->
    <div class="card-container">
        <div class="card">
            <h5 class="card-header">Tejidos</h5>
            <img src="../imgs/tejidos.jpg" alt="Tejidos Image" class="card-img4">
            <div class="card-body">
                <a href="../content/more_tejidos.php" class="btn">View Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>
<script src="../js/script.js"></script> <!-- Fixed script path -->
</body>
</html>