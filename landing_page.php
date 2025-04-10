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

// Fetch announcements
try {
    $stmt = $conn->prepare("
        SELECT a.*, CONCAT(ac.firstname, ' ', ac.lastname) AS created_by_name
        FROM announcements a
        LEFT JOIN accounts ac ON a.created_by = ac.account_id
        ORDER BY a.created_at DESC
        LIMIT 1
    ");
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching announcements: " . $e->getMessage();
}

// Fetch carousel images
try {
    $stmt = $conn->prepare("SELECT * FROM carousel_images ORDER BY display_order ASC, created_at DESC");
    $stmt->execute();
    $carousel_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching carousel images: " . $e->getMessage();
}

// Fetch magazines
try {
    $stmt = $conn->prepare("SELECT * FROM e_magazines ORDER BY created_at DESC");
    $stmt->execute();
    $magazines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching magazines: " . $e->getMessage();
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
       <!-- Hero Carousel -->
<div class="hero-carousel-container">
    <div class="hero-carousel">
        <div class="hero-carousel-inner">
            <?php 
            if (!empty($carousel_images)) {
                $carousel_images = array_slice($carousel_images, 0, 3); // Limit to 3 images
                foreach ($carousel_images as $carousel_image) { 
                    $image_url = htmlspecialchars($carousel_image['image_url'] ?? 'default.jpg');
            ?>
                <img src="uploaded_img/<?php echo $image_url; ?>" alt="Carousel Image">
            <?php 
                }
            } else { 
            ?>
                <p>No carousel items available at the moment.</p>
            <?php } ?>
        </div>

        <button class="hero-carousel-control prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="hero-carousel-control next" onclick="moveSlide(1)">&#10095;</button>

        <div class="hero-carousel-indicators">
            <?php 
            if (!empty($carousel_images)) {
                foreach ($carousel_images as $index => $carousel_image) { 
            ?>
                <span class="hero-indicator <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></span>
            <?php 
                }
            } 
            ?>
        </div>
    </div>
</div>


        <!-- Purpose Card -->
        <div class="purpose-card">
            <h2 class="purpose-title">Purpose of The University Digest</h2>
            <p class="purpose-text">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
            <div class="info-icon-text">
                <i class="fas fa-school"></i> <span>Western Mindanao <br> State University</span>
                <i class="fas fa-map-marker-alt"></i> <span>WMSU <br> Campus A</span>
                <i class="fas fa-calendar-alt"></i> <span>EST. <br> MCMLXXVIII</span>
                <i class="fas fa-clock"></i> <span>Mon-Sat, <br> 8AM-5PM</span>
            </div>
        </div>

        <!-- About Card -->
        <div class="about-card">
            <div class="about-content">
                <h2 class="about-title">About The University Digest</h2>
                <p class="about-text">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>
                <a href="about_us.php" class="about-button">More About Us</a>
            </div>
            <div class="about-image">
                <img src="imgs/logo_trans.png" alt="Sample Image">
            </div>
        </div>

        <!-- Announcements Card -->
        <div class="card-announcements-container">
            <div class="card-announcements">
                <h5 class="card-announcements-header">Announcements</h5>
                <div class="card-announcements-body">
                    <?php if (!empty($announcements)) {
                        $latest_announcement = $announcements[0]; ?>
                        <h2 class="card-announcements-title"><?php echo htmlspecialchars($latest_announcement['title']); ?></h2>
                        <p class="card-announcements-content"><?php echo htmlspecialchars($latest_announcement['content']); ?></p>
                        <?php if (!empty($latest_announcement['image'])) { ?>
                            <img class="card-announcements-image" src="uploaded_img/<?php echo htmlspecialchars($latest_announcement['image']); ?>">
                        <?php } ?>
                        <p class="card-announcements-footer">
                            Posted by: <?php echo htmlspecialchars($latest_announcement['created_by_name']); ?>
                            on <?php echo date('M j, Y h:i A', strtotime($latest_announcement['created_at'])); ?>
                        </p>
                        <a href="user_content/more_announcement.php" class="btn-announcement">View More</a>
                    <?php } else { ?>
                        <p class="card-announcements-content">No announcements available at the moment.</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Articles Section -->
        <div class="card-3-divider">
            <h2 class="articles-header">ARTICLES</h2>
            <div class="card-3">
                <div class="smaller-card">
                    <i class="fa-solid fa-book-open-reader"></i>
                    <h4>Feature</h4>
                    <p>Short description 2</p>
                    <a href="user_content/feature.php" class="explore-button">Explore</a>
                </div>
                <div class="smaller-card">
                    <i class="fas fa-newspaper"></i>
                    <h4>News</h4>
                    <p>Short description 1</p>
                    <a href="user_content/news.php" class="explore-button">Explore</a>
                </div>
                <div class="smaller-card">
                    <i class="fas fa-pencil-alt"></i>
                    <h4>Editorial</h4>
                    <p>Short description 3</p>
                    <a href="user_content/editorial.php" class="explore-button">Explore</a>
                </div>
                <div class="smaller-card">
                    <i class="fas fa-asterisk"></i>
                    <h4>Miscellaneous</h4>
                    <p>Short description 4</p>
                    <a href="user_content/misc.php" class="explore-button">Explore</a>
                </div>
            </div>
        </div>

        <!-- Magazine Carousel -->
        <div class="magazine-carousel-container">
            <h2>MAGAZINES</h2>
            <div class="magazine-carousel">
                <div class="magazine-track">
                    <?php if (!empty($magazines)) {
                        foreach ($magazines as $magazine) { ?>
                            <div class="magazine-card">
                                <?php if (!empty($magazine['image'])) { ?>
                                    <img src="uploaded_img/<?php echo htmlspecialchars($magazine['image']); ?>" alt="<?php echo htmlspecialchars($magazine['title']); ?>">
                                <?php } else { ?>
                                    <img src="imgs/default_magazine.jpg" alt="Default Magazine Image">
                                <?php } ?>
                                <h4><?php echo htmlspecialchars($magazine['title']); ?></h4>
                                <p><?php echo htmlspecialchars($magazine['context']); ?></p>
                                <a href="<?php echo htmlspecialchars($magazine['link']); ?>"></a>
                            </div>
                        <?php }
                    } else { ?>
                        <p>No magazines available at the moment.</p>
                    <?php } ?>
                </div>
            </div>
            <button class="magazine-carousel-btn prev-magazine-btn" onclick="moveMagazineSlide(-1)">&#10094;</button>
            <button class="magazine-carousel-btn next-magazine-btn" onclick="moveMagazineSlide(1)">&#10095;</button>
        </div>

        <!-- Add spacing before footer -->
        <div style="margin-bottom: 50px;"></div>
    </div>

    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <!-- Scripts -->
     <script src="js/carousel.js"></script>
    <script src="js/script.js"></script>
    <script src="js/landing.js"></script>
    <script src="js/mags.js"></script>
</body>
</html>
