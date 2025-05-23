<?php

require_once '../components/connect.php';

$db = new Database();
$conn = $db->connect();

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location: ../admin/admin_login.php');
    exit();
}

// Check if the user is a super admin
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'superadmin') {
    // Redirect to appropriate page for unauthorized access
    header('location: ../access_denied.php');
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch profile data
$select_profile = $conn->prepare("SELECT firstname, lastname FROM `accounts` WHERE account_id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if (!$fetch_profile) {
    echo '<p class="error">Profile not found. Please contact support.</p>';
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />

    <!-- Custom CSS File -->
    <link rel="stylesheet" href="../css/admin_style.css" />
</head>

<body>

<?php include '../components/superadmin_header.php'; ?>

<section class="dashboard">

    <h1 class="heading">Dashboard</h1>

    <div class="box-container">

        <div class="box">
            <h3>Welcome!</h3>
            <p><?= htmlspecialchars($fetch_profile['firstname'] . ' ' . $fetch_profile['lastname']); ?></p>
            <a href="superadmin_update_profile.php" class="btn">Update Profile</a>
        </div>

        <!-- Published Posts Section -->
        <div class="box">
            <?php
            $select_posts = $conn->prepare("SELECT COUNT(*) FROM `posts` WHERE created_by = ? AND status = 'published'");
            $select_posts->execute([$admin_id]);
            $numbers_of_posts = $select_posts->fetchColumn();
            ?>
            <h3><?= $numbers_of_posts; ?></h3>
            <p>Published Posts</p>
            <a href="../superadmin_content/sa_add_posts.php" class="btn">Add New Post</a>
        </div>

        <!-- Tejido Section -->
        <div class="box">
            <?php
            $select_tejido = $conn->prepare("SELECT COUNT(*) FROM `tejido` WHERE created_by = ?");
            $select_tejido->execute([$admin_id]);
            $numbers_of_tejido = $select_tejido->fetchColumn();
            ?>
            <h3><?= $numbers_of_tejido; ?></h3>
            <p>Tejido Added</p>
            <a href="../superadmin_content/sa_add_tejido.php" class="btn">Add Tejido</a>
        </div>

        <!-- Tejido Drafts Section -->
        <div class="box">
            <?php
            $select_draft_tejido = $conn->prepare("SELECT COUNT(*) FROM `tejido` WHERE created_by = ? AND status = 'draft'");
            $select_draft_tejido->execute([$admin_id]);
            $numbers_of_draft_tejido = $select_draft_tejido->fetchColumn();
            ?>
            <h3><?= $numbers_of_draft_tejido; ?></h3>
            <p>Tejido Drafts</p>
            <a href="../superadmin_content/sa_view_tejido.php" class="btn">Manage Tejido Drafts</a>
        </div>

        <div class="box">
        <?php
         $select_articles = $conn->prepare("SELECT COUNT(*) FROM `articles` WHERE created_by = ?");
         $select_articles->execute([$admin_id]);
         $numbers_of_articles = $select_articles->fetchColumn();
        ?>
        <h3><?= $numbers_of_articles; ?></h3>
        <p>Articles</p>
        <a href="../superadmin_content/sa_add_articles.php" class="btn">Add Articles</a>
       </div>
       
        <!-- Article Drafts Section -->
        <div class="box">
            <?php
            $select_draft_articles = $conn->prepare("SELECT COUNT(*) FROM `articles` WHERE created_by = ? AND status = 'draft'");
            $select_draft_articles->execute([$admin_id]);
            $numbers_of_draft_articles = $select_draft_articles->fetchColumn();
            ?>
            <h3><?= $numbers_of_draft_articles; ?></h3>
            <p>Article Drafts</p>
            <a href="../superadmin_content/sa_view_article.php" class="btn">Manage Article Drafts</a>
        </div>

        <!-- Categories Management Section -->
        <div class="box">
            <?php
            try {
                $select_categories = $conn->prepare("SELECT COUNT(*) FROM category");
                $select_categories->execute();
                $numbers_of_categories = $select_categories->fetchColumn();
            } catch (PDOException $e) {
                $numbers_of_categories = 0;
            }
            ?>
            <h3><?= $numbers_of_categories; ?></h3>
            <p>Categories</p>
            <a href="../superadmin_content/sa_edit_categories.php" class="btn">
                <i class="fas fa-tags"></i> Manage Categories
            </a>
        </div>
       
        <!-- Draft Posts Section -->
        <div class="box">
            <?php
            $select_deactive_posts = $conn->prepare("SELECT COUNT(*) FROM `posts` WHERE created_by = ? AND status = 'draft'");
            $select_deactive_posts->execute([$admin_id]);
            $numbers_of_deactive_posts = $select_deactive_posts->fetchColumn();
            ?>
            <h3><?= $numbers_of_deactive_posts; ?></h3>
            <p>Posts Drafts</p>
            <a href="../superadmin_content/sa_view_posts.php" class="btn">Manage Posts Drafts</a>
        </div>

        <!-- Users Account Section -->
        <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT COUNT(*) FROM `accounts` WHERE role = 'user'");
            $select_users->execute();
            $numbers_of_users = $select_users->fetchColumn();
            ?>
            <h3><?= $numbers_of_users; ?></h3>
            <p>User Accounts</p>
            <a href="sa_user_accounts_management.php" class="btn">See Users</a>
        </div>

        
        <!-- Admin Accounts Section -->
        <div class="box">
            <?php
            $select_admins = $conn->prepare("SELECT COUNT(*) FROM `accounts` WHERE role IN ('superadmin', 'subadmin')");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->fetchColumn();
            ?>
            <h3><?= $numbers_of_admins; ?></h3>
            <p>Admin Accounts</p>
            <a href="../superadmin_content/sa_admin_accounts.php" class="btn">Manage Admins</a>
        </div>

        <!-- Comments Section -->
        <div class="box">
            <?php
            $select_comments = $conn->prepare("SELECT COUNT(*) FROM `comments` WHERE commented_by = ?");
            $select_comments->execute([$admin_id]);
            $numbers_of_comments = $select_comments->fetchColumn();
            ?>
            <h3><?= $numbers_of_comments; ?></h3>
            <p>Comments Added</p>
            <a href="../superadmin_content/sa_comments.php" class="btn">See Comments</a>
        </div>

        <!-- Likes Section -->
        <div class="box">
            <?php
            // Count likes received on all your posts
            $select_likes = $conn->prepare("
                SELECT COUNT(l.like_id) 
                FROM `likes` l
                JOIN `posts` p ON l.post_id = p.post_id
                WHERE p.created_by = ?
            ");
            $select_likes->execute([$admin_id]);
            $numbers_of_likes = $select_likes->fetchColumn();
            ?>
            <h3><?= $numbers_of_likes; ?></h3>
            <p>Likes Received</p>
            <a href="../superadmin_content/sa_total_likes.php" class="btn">See Post Likes</a>
        </div>

        <!-- E-Magazine Management Section -->
        <div class="box">
            <?php
            try {
                // First check if the table exists
                $table_check = $conn->query("SHOW TABLES LIKE 'e_magazines'");
                if ($table_check->rowCount() > 0) {
                    $select_magazines = $conn->prepare("SELECT COUNT(*) FROM `e_magazines`");
                    $select_magazines->execute();
                    $numbers_of_magazines = $select_magazines->fetchColumn();
                    echo "<!-- Debug: Found $numbers_of_magazines e-magazines -->";
                } else {
                    echo "<!-- Debug: e_magazines table not found -->";
                    $numbers_of_magazines = 0;
                }
            } catch (PDOException $e) {
                echo "<!-- Debug Error: " . htmlspecialchars($e->getMessage()) . " -->";
                $numbers_of_magazines = 0;
            }
            ?>
            <h3><?= $numbers_of_magazines; ?></h3>
            <p>E-Magazines</p>
            <a href="../superadmin_content/sa_manage_emagazines.php" class="btn">
                <i class="fas fa-book"></i> Manage E-Magazines
            </a>
        </div>
        
        <!-- Announcements Section -->
        <div class="box">
            <?php
            try {
                // First check if the table exists
                $table_check = $conn->query("SHOW TABLES LIKE 'announcements'");
                if ($table_check->rowCount() > 0) {
                    $select_announcements = $conn->prepare("SELECT COUNT(*) FROM `announcements`");
                    $select_announcements->execute();
                    $numbers_of_announcements = $select_announcements->fetchColumn();
                    echo "<!-- Debug: Found $numbers_of_announcements announcements -->";
                } else {
                    echo "<!-- Debug: announcements table not found -->";
                    $numbers_of_announcements = 0;
                }
            } catch (PDOException $e) {
                echo "<!-- Debug Error: " . htmlspecialchars($e->getMessage()) . " -->";
                $numbers_of_announcements = 0;
            }
            ?>
            <h3><?= $numbers_of_announcements; ?></h3>
            <p>Announcements</p>
            <a href="../superadmin_content/sa_manage_announcements.php" class="btn">
                <i class="fas fa-bullhorn"></i> Add Announcements
            </a>
        </div>

    </div>

</section>

<script src="../js/admin_script.js"></script>

</body>

</html>
