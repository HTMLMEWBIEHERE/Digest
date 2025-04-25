<?php
include '../components/connect.php';  // Fixed path with ../

$db = new Database();
$conn = $db->connect();

// Start session if needed
session_start();

// Fetch all organization categories
$select_categories = $conn->prepare("SELECT * FROM org_categories ORDER BY category_name ASC");
$select_categories->execute();
$categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - The University Digest</title>
    <link rel="stylesheet" href="../css/about_us.css">  <!-- Fixed path with ../ -->
</head>
<body>
    <?php include '../components/user_header.php'; ?>  <!-- Fixed path with ../ -->
    
    <div class="main">
        <div class="card-2">
            <div class="card-content">
                <h2 style="color:#4F0003;">About The University Digest</h2>
                <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="logo-image">
                <img src="../imgs/logo_trans.png" alt="Sample Image">  <!-- Fixed path with ../ -->
            </div>
        </div>

        <?php 
        // Loop through each category and display its members
        foreach ($categories as $category) {
            // Fetch members for this category who are not deleted
            $select_members = $conn->prepare("
                SELECT * FROM organizational_chart 
                WHERE category_id = ? AND is_deleted = 0 
                ORDER BY position, date_appointed DESC
            ");
            $select_members->execute([$category['category_id']]);
            $members = $select_members->fetchAll(PDO::FETCH_ASSOC);

            // Group members by position
            $grouped_members = [];
            foreach ($members as $member) {
                $grouped_members[$member['position']][] = $member;
            }

            // Only display section if members exist
            if (!empty($grouped_members)) {
        ?>
        <div class="writers-container">
            <h1><?= htmlspecialchars($category['category_name']) ?></h1>
            
            <?php 
            // Iterate through each position group
            foreach ($grouped_members as $position => $position_members) { 
            ?>
            <div class="position-group">
                <h2><?= htmlspecialchars($position) ?></h2>
                <div class="editorial-cards">
                    <?php foreach ($position_members as $member) { 
                        // Get image directly from database field
                        if (!empty($member['image'])) {
                            // Use the image path stored in the database, but ensure proper path prefix
                            $member_image = '../' . htmlspecialchars($member['image']);
                        } else {
                            // Default image if no image in database
                            $member_image = '../imgs/member.jpg';
                        }
                    ?>
                    <div class="card">
                        <div class="card-image">
                            <img src="<?= $member_image; ?>" alt="<?= htmlspecialchars($member['name']); ?>" onerror="this.onerror=null;this.src='../imgs/member.jpg';">
                        </div>
                        <div class="card-content">
                            <h2><?= htmlspecialchars($member['name']); ?></h2>
                            <p><?= htmlspecialchars($position); ?></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php 
            } 
        } 
        ?>
    </div>  

    <div style="margin-bottom: 50px;"></div>
    
    <?php include '../components/footer.php'; ?>  <!-- Fixed path with ../ -->
    
    <script src="../js/script.js"></script>  <!-- Fixed path with ../ -->
</body>
</html>
