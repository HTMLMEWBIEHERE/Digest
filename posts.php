<?php
include 'components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

if (isset($_SESSION['account_id'])) {
    $account_id = $_SESSION['account_id'];
} else {
    $account_id = '';
}

include 'components/like_post.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="posts-container">
    <h1 class="heading">All Posts</h1>

    <div class="box-container">
        <?php
        try {
            // Fetch posts with username instead of firstname and lastname
            $select_posts = $conn->prepare("
                SELECT posts.*, accounts.user_name AS author_user_name, category.name AS category_name
                FROM `posts` 
                JOIN `accounts` ON posts.created_by = accounts.account_id 
                JOIN `category` ON posts.category_id = category.category_id 
                WHERE posts.status = ? 
                ORDER BY posts.created_at DESC 
            ");
            $select_posts->execute(['published']);

            if ($select_posts->rowCount() > 0) {
                while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                    $post_id = $fetch_posts['post_id'];

                    // Count comments for the post
                    $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
                    $count_post_comments->execute([$post_id]);
                    $total_post_comments = $count_post_comments->rowCount();

                    // Count likes for the post
                    $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
                    $count_post_likes->execute([$post_id]);
                    $total_post_likes = $count_post_likes->rowCount();

                    // Check if the current user has liked the post
                    $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE account_id = ? AND post_id = ?");
                    $confirm_likes->execute([$account_id, $post_id]);

                    // Truncate content to 2-3 sentences
                    $content = $fetch_posts['content'];
                    $content_preview = implode('. ', array_slice(explode('. ', $content), 0, 2)) . '.';
        ?>
                    <form class="box" method="post">
                        <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                        <input type="hidden" name="account_id" value="<?= $fetch_posts['created_by']; ?>">
                        <div class="post-admin">
                            <i class="fas fa-user"></i>
                            <div>
                                <a href="author_posts.php?author=<?= $fetch_posts['created_by']; ?>">
                                    <?= htmlspecialchars($fetch_posts['author_user_name'], ENT_QUOTES, 'UTF-8'); ?> <!-- Display username here -->
                                </a>
                                <div><?= htmlspecialchars($fetch_posts['created_at'], ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>
                        </div>

                        <?php if ($fetch_posts['image'] != '') { ?>
                            <img src="uploaded_img/<?= htmlspecialchars($fetch_posts['image'], ENT_QUOTES, 'UTF-8'); ?>" class="post-image" alt="">
                        <?php } ?>

                        <div class="post-title"><?= htmlspecialchars($fetch_posts['title'] ?? 'No Title', ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="post-content content-150"><?= htmlspecialchars($content_preview, ENT_QUOTES, 'UTF-8'); ?></div>
                        <a href="view_post.php?post_id=<?= htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8'); ?>" class="inline-btn">Read More</a>
                        <a href="category.php?category=<?= htmlspecialchars($fetch_posts['category_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="post-cat">
                            <i class="fas fa-tag"></i> <span><?= htmlspecialchars($fetch_posts['category_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                        </a>
                        <div class="icons">
                            <a href="view_post.php?post_id=<?= htmlspecialchars($post_id, ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span>
                            </a>
                            <button type="submit" name="like_post">
                                <i class="fas fa-heart" style="<?= $confirm_likes->rowCount() > 0 ? 'color:var(--red);' : ''; ?>"></i>
                                <span>(<?= $total_post_likes; ?>)</span>
                            </button>
                        </div>
                    </form>
        <?php
                }
            } else {
                echo '<p class="empty">No posts added yet!</p>';
            }
        } catch (PDOException $e) {
            die("Error fetching posts: " . $e->getMessage()); // Display query error
        }
        ?>
    </div>
</section>

<script src="js/script.js"></script>
</body>
</html>