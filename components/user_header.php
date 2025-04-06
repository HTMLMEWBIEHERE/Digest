<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$account_id = $_SESSION['account_id'] ?? null;
$user_name = $_SESSION['user_name'] ?? null;
$user_role = $_SESSION['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The University Digest</title>
    <link rel="stylesheet" href="css/userheader.css">
    <!-- Updated Material Icons Import -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<header>
    <div class="logo-container">
        <a href="landing_page.php">
            <img src="imgs/logo.png" alt="The University Digest Logo">
            <div class="logo"><h3>The University Digest</h3></div>
        </a>
    </div>
    <div class="search-box">
        <input type="text" name="search" id="search" placeholder="Search">
        <button type="submit"><span class="material-symbols-outlined">search</span></button>
        <div id="search-results"></div>
    </div>

    <div class="header-right">
        <ul class="nav-menu">
            <li><a href="home.php">Home</a></li>
            <li><a href="all_category.php">Announcements</a></li>
            <li><a href="authors.php">Authors</a></li>
            <li><a href="about_us.php">About Us</a></li>
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
        <a href="../update_profile.php" class="btn">Update Profile</a>
        <?php if($fetch_profile['role'] == 'superadmin' || $fetch_profile['role'] == 'subadmin'): ?>
            <a href="admin/dashboard.php" class="option-btn">Admin Dashboard</a>
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
    // Toggle profile dropdown when user button is clicked
    document.querySelector('#user-btn').onclick = () => {
        document.querySelector('.profile').classList.toggle('active');
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#user-btn') && !e.target.closest('.profile')) {
            document.querySelector('.profile').classList.remove('active');
        }
    });
</script>
<script src="js/script.js"></script>
</body>
</html>