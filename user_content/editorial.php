<?php
// Include database connection
include '../components/connect.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize variables
$db = new Database();
$conn = $db->connect();
$category_name = "Editorial"; // Page title

// Get the editorial category ID from database
$get_editorial_category = $conn->prepare("SELECT category_id FROM category WHERE name LIKE '%editorial%' LIMIT 1");
$get_editorial_category->execute();
if($get_editorial_category->rowCount() > 0) {
    $current_category = $get_editorial_category->fetchColumn();
} else {
    $current_category = 3; // Fallback ID - adjust this to your actual editorial category ID
}

// Search and sort functionality
$search_term = isset($_GET['search']) ? filter_var($_GET['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Build query for editorial articles
$query = "SELECT a.*, 
          CONCAT(ac.firstname, ' ', COALESCE(ac.middlename, ''), ' ', ac.lastname) AS author_name 
          FROM `articles` a 
          LEFT JOIN `accounts` ac ON a.created_by = ac.account_id
          LEFT JOIN `category` c ON a.category_id = c.category_id
          WHERE a.category_id = :category_id AND a.status = 'published'";

// Apply search filter if provided
if (!empty($search_term)) {
    $query .= " AND (a.title LIKE :search OR a.content LIKE :search)";
}

// Apply sorting
switch ($sort) {
    case 'oldest':
        $query .= " ORDER BY a.created_at ASC";
        break;
    case 'a-z':
        $query .= " ORDER BY a.title ASC";
        break;
    case 'z-a':
        $query .= " ORDER BY a.title DESC";
        break;
    case 'most-viewed':
        $query .= " ORDER BY a.views DESC";
        break;
    case 'newest':
    default:
        $query .= " ORDER BY a.created_at DESC";
        break;
}

// Prepare and execute the query
$select_editorial = $conn->prepare($query);
$select_editorial->bindParam(':category_id', $current_category, PDO::PARAM_INT);

if (!empty($search_term)) {
    $search_param = "%$search_term%";
    $select_editorial->bindParam(':search', $search_param, PDO::PARAM_STR);
}

$select_editorial->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $category_name ?> | The University Digest</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/article_style.css">
    <link rel="stylesheet" href="../css/userheader.css">
    <link rel="stylesheet" href="../css/footer.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include_once '../components/user_header.php'; ?>

<div class="articles-section">
    <div class="article-card-wrapper">
        <div class="card-articles-header"><?= strtoupper($category_name) ?></div>
        
        <div class="filter-container">
        <form action="" method="GET" class="filter-form">
            <div class="input-field search-field">
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" placeholder="Search editorials..." class="box" value="<?= htmlspecialchars($search_term); ?>">
            </div>

            <button type="button" class="inline-btn" onclick="openModal()">
                <i class="fas fa-sort"></i> 
            </button>

            <a href="editorial.php?search=&sort=newest" class="inline-option-btn">
                <i class="fas fa-undo"></i> 
            </a>
        </form>
    </div>

        <!-- Editorial Articles Display Section -->
        <section class="articles">
            <div class="card-articles-container">
                <?php
                if ($select_editorial->rowCount() > 0) {
                    $editorial_articles = $select_editorial->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($editorial_articles as $article) {
                        ?>
                        <div class="card-article">
                            <div class="card-article-body">
                                <h2 class="card-article-title"><?= htmlspecialchars($article['title']); ?></h2>
                                
                                <?php if (!empty($article['image'])): ?>
                                    <img class="card-article-image" src="../uploaded_img/<?= htmlspecialchars($article['image']); ?>" alt="<?= htmlspecialchars($article['title']); ?>">
                                <?php endif; ?>
                                
                                <!-- Short excerpt that's visible by default -->
                                <div class="card-article-excerpt short-content">
                                    <?= htmlspecialchars(substr($article['content'], 0, 300)) . '...'; ?>
                                </div>
                                
                                <!-- Full content that's initially hidden -->
                                <div class="card-article-full-content" style="display: none;">
                                    <?= nl2br(htmlspecialchars($article['content'])); ?>
                                </div>
                                
                                <div class="card-article-footer">
                                    <span class="article-author">By: <?= htmlspecialchars($article['author_name'] ?? 'Unknown'); ?></span>
                                    <span class="article-date"><?= date('M j, Y', strtotime($article['created_at'])); ?></span>
                                </div>
                                
                                <div class="button-container">
                                    <button class="read-more-btn toggle-content" data-article-id="<?= $article['article_id']; ?>">Read More</button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="no-articles">No editorial articles available at the moment.</p>';
                }
                ?>
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Filter Options</h2>
        <form action="" method="GET">
            <div class="input-field">
                <label for="sort">Sort by:</label>
                <select name="sort" class="box">
                    <option value="newest" <?= ($sort === 'newest') ? 'selected' : ''; ?>>Newest First</option>
                    <option value="oldest" <?= ($sort === 'oldest') ? 'selected' : ''; ?>>Oldest First</option>
                    <option value="a-z" <?= ($sort === 'a-z') ? 'selected' : ''; ?>>A-Z</option>
                    <option value="z-a" <?= ($sort === 'z-a') ? 'selected' : ''; ?>>Z-A</option>
                </select>
            </div>
            <div class="modal-buttons">
                <button type="button" class="inline-option-btn" onclick="closeModal()">Cancel</button>
                <button type="submit" class="inline-btn">Confirm</button>
            </div>
        </form>
    </div>
</div>

<?php include_once '../components/footer.php'; ?>

<script src="../js/news.js"></script>
<script src="../js/filters.js"></script>


</body>
</html>