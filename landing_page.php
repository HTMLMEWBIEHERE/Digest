<?php
// Add error reporting for troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection
include 'components/connect.php';

// Start database connection
$db = new Database();
$conn = $db->connect();

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The University Digest - Home</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="css/landing_page.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/userheader.css">
    <link rel="stylesheet" href="css/footer.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <div class="main-content">
        <div class="hero-carousel-container">
            <div class="hero-carousel">
                <div class="hero-carousel-inner">
                    <div class="hero-carousel-item active">
                        <img src="imgs/c3.jpg" alt="Slide 1">
                    </div>
                    <div class="hero-carousel-item">
                        <img src="imgs/c1.jpg" alt="Slide 2">
                    </div>
                    <div class="hero-carousel-item">
                        <img src="imgs/cover.jpg" alt="Slide 3">
                    </div>
                </div>

                <button class="hero-carousel-control prev" onclick="moveSlide(-1)">&#10094;</button>
                <button class="hero-carousel-control next" onclick="moveSlide(1)">&#10095;</button>

                <div class="hero-carousel-indicators">
                    <span class="hero-indicator active" onclick="goToSlide(0)"></span>
                    <span class="hero-indicator" onclick="goToSlide(1)"></span>
                    <span class="hero-indicator" onclick="goToSlide(2)"></span>
                </div>
            </div>

            <!-- Overlapping Card - renamed -->
            <div class="purpose-card">
                <h2 class="purpose-title">Purpose of The University Digest</h2>
                <br>
                <p class="purpose-text">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <br>
                <div class="info-icon-text">
                    <i class="fas fa-school"></i> <span>Western Mindanao <br> State University</span>
                    <i class="fas fa-map-marker-alt"></i> <span>WMSU <br> Campus A</span>
                    <i class="fas fa-calendar-alt"></i> <span>EST. <br> MCMLXXVIII</span>
                    <i class="fas fa-clock"></i> <span>Mon-Sat, <br> 8AM-5PM</span>
                </div>
            </div>
        </div>

        <!-- About Card - renamed -->
        <div class="about-card">
            <div class="about-content">
                <h2 class="about-title">About The University Digest</h2>
                <p class="about-text">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <br>
                <a href="about_us.php" class="about-button">More About Us</a>
            </div>
            <div class="about-image">
                <img src="imgs/logo_trans.png" alt="Sample Image">
            </div>
        </div>

        <!-- Announcements Card -->
        <div class="card-announcements">
            <div class="card-content">
                <h1 style="color:#4F0003;">Announcements</h1>
                <p>"Stay updated with the latest announcements and news from the university. From important dates to upcoming events, find all the information you need right here."</p>
                <br>
                <a href="content/announcements.php" class="read-more-button">View Announcements</a>
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
                    <a href="content/news.php" class="explore-button">Explore</a>
                </div>
                <div class="smaller-card">
                    <i class="fa-solid fa-book-open-reader"></i>
                    <h4>Comics</h4>
                    <p>Short description 2</p>
                    <a href="content/comics.php" class="explore-button">Explore</a>
                </div>
                <div class="smaller-card">
                    <i class="fas fa-pencil-alt"></i>
                    <h4>Editorial</h4>
                    <p>Short description 3</p>
                    <a href="content/editorial.php" class="explore-button">Explore</a>
                </div>
                <div class="smaller-card">
                    <i class="fas fa-asterisk"></i>
                    <h4>Miscellaneous</h4>
                    <p>Short description 4</p>
                    <a href="content/misc.php" class="explore-button">Explore</a>
                </div>
            </div>
        </div>

        <div class="card-container">
            <div class="card">
                <h5 class="card-header">TEJIDOS</h5>
                <img src="imgs/tejidos.jpg" alt="Tejidos Image" class="card-img4">
                <div class="card-body">
                    <a href="content/more_tejidos.php" class="btn">View Now</a>
                </div>
            </div>
        </div>

        <!-- Magazine Carousel -->
        <div class="magazine-carousel-container">
            <h2>MAGAZINES</h2>
            <div class="magazine-carousel">
                <div class="magazine-track">
                    <div class="magazine-card">
                        <img src="imgs/mag1.jpg" alt="Magazine 1">
                        <h4>Magazine 1</h4>
                        <p>Short description 1</p>
                    </div>
                    <div class="magazine-card">
                        <img src="imgs/mag2.jpg" alt="Magazine 2">
                        <h4>Magazine 2</h4>
                        <p>Short description 2</p>
                    </div>
                    <div class="magazine-card">
                        <img src="imgs/mag1.jpg" alt="Magazine 3">
                        <h4>Magazine 3</h4>
                        <p>Short description 3</p>
                    </div>
                    <div class="magazine-card">
                        <img src="imgs/mag2.jpg" alt="Magazine 4">
                        <h4>Magazine 4</h4>
                        <p>Short description 4</p>
                    </div>
                    <div class="magazine-card">
                        <img src="imgs/mag1.jpg" alt="Magazine 5">
                        <h4>Magazine 5</h4>
                        <p>Short description 5</p>
                    </div>
                    <div class="magazine-card">
                        <img src="imgs/mag2.jpg" alt="Magazine 6">
                        <h4>Magazine 6</h4>
                        <p>Short description 6</p>
                    </div>
                </div>
            </div>
            <button class="magazine-carousel-btn prev-magazine-btn" onclick="moveMagazineSlide(-1)">&#10094;</button>
            <button class="magazine-carousel-btn next-magazine-btn" onclick="moveMagazineSlide(1)">&#10095;</button>
        </div>

        <!-- Add spacing div before footer -->
        <div style="margin-bottom: 50px;"></div>
    </div>

    <!-- Footer - fixed path and removed __DIR__ which was causing issues -->
    <?php include 'components/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/script.js"></script>
    <script src="js/landing.js"></script>
    <script src="js/mags.js"></script>
</body>
</html>