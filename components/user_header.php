<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$account_id = $_SESSION['account_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? null;
$user_role = $_SESSION['role'] ?? null;

// Fetch about us content for the logo
try {
    // Only fetch if the Database class exists and connection is available
    if (class_exists('Database') && isset($conn)) {
        $about_query = $conn->prepare("SELECT image FROM about_us ORDER BY about_id DESC LIMIT 1");
        $about_query->execute();
        $about_us = $about_query->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    // Silently fail - will use default logo
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The University Digest</title>
    <link rel="stylesheet" href="../css/user_header.css">
    
    <!-- Updated Material Icons Import -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<header>
    <div class="logo-container">
        <a href="../user_content/landing_page.php">
        <img src="<?php echo htmlspecialchars($about_us['image'] ? '../uploads/' . $about_us['image'] : '../imgs/logo_trans.png'); ?>" alt="About Us Image" class="footer-logo">
        <div class="logo"><h3>The University Digest</h3></div>
        </a>
    </div>

    <div class="header-right">
        <ul class="nav-menu">
            <li><a href="../user_content/home.php">Home</a></li>
            <li><a href="../user_content/more_announcement.php">Announcements</a></li>
            <li><a href="../user_content/authors.php">Creators</a></li>
            <li><a href="../user_content/about_us.php">About</a></li>
            <li class="dropdown">
            <a href="#" class="dropbtn">Articles</a>
            <div class="dropdown-content">
                <a href="../user_content/news.php">News</a>
                <a href="../user_content/comics.php">Comics</a>
                <a href="../user_content/editorial.php">Editorial</a>
                <a href="../user_content/misc.php">Miscellaneous</a>
            </div>
        </li>
        <li><a href="../user_content/more_tejidos.php">Tejidos</a></li>
            <li><a href="../user_content/more_magazines.php">Magazines</a></li>
        </ul>
        
        <!-- Add user icon here -->
        <div class="user-icon-container">
            <div id="user-btn" class="fas fa-user"></div>
        </div>
    </div>
</header>

<!-- Profile dropdown -->
<div class="profile">
    <?php
        if($account_id) {
            // Using account_id instead of user_id
            $select_profile = $conn->prepare("SELECT * FROM `accounts` WHERE account_id = ?");
            $select_profile->execute([$account_id]);
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    ?>
        <p class="name"><?= htmlspecialchars($fetch_profile['firstname'] . ' ' . $fetch_profile['lastname']); ?></p>
        <a href="update_profile.php" class="btn">Update Profile</a>
        <?php if($fetch_profile['role'] == 'superadmin' || $fetch_profile['role'] == 'subadmin'): ?>
            <a href="../admin/dashboard.php" class="option-btn">Admin Dashboard</a>
        <?php endif; ?>
        <a href="../components/user_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">Logout</a>
    <?php
            } else {
    ?>
        <p class="name">Please login first!</p>
        <div class="flex-btn">
            <a href="login.php" class="option-btn">Login</a>
            <a href="../register.php" class="option-btn">Register</a>
        </div>
    <?php
            }
        } else {
    ?>
        <p class="name">Please login first!</p>
        <div class="flex-btn">
            <a href="login.php" class="option-btn">Login</a>
            <a href="register.php" class="option-btn">Register</a>
        </div>
    <?php
        }
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Improved profile dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const userBtn = document.querySelector('#user-btn');
        const profileDropdown = document.querySelector('.profile');
        let isScrolling = false;
        
        // Toggle profile dropdown when user button is clicked
        userBtn.onclick = function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
            
            if(profileDropdown.classList.contains('active')) {
                // Get position of the user button
                const rect = userBtn.getBoundingClientRect();
                
                // Position dropdown relative to viewport
                profileDropdown.style.position = 'fixed';
                profileDropdown.style.top = (rect.bottom + 10) + 'px';
                profileDropdown.style.right = '20px';
                profileDropdown.style.zIndex = '1000';
            }
        };
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if(!e.target.closest('#user-btn') && !e.target.closest('.profile')) {
                profileDropdown.classList.remove('active');
            }
        });
        
        // Hide dropdown when scrolling begins
        let lastScrollTop = window.pageYOffset || document.documentElement.scrollTop;
        window.addEventListener('scroll', function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            // Check if we're actually scrolling (not just tiny movements)
            if(Math.abs(lastScrollTop - currentScroll) > 5) {
                profileDropdown.classList.remove('active');
            }
            
            lastScrollTop = currentScroll;
        }, { passive: true });
    });
</script>

<!-- Keep the script.js reference but modify script.js to avoid conflicts -->
<script src="../js/script.js"></script>
</body>
</html>