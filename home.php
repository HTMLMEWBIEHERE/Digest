<?php
include 'components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

// Initialize $user_id for non-logged-in users
$user_id = '';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
}

include 'components/like_post.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">    
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    <section class="posts-container">
        <h1 class="headingpost">Latest Posts</h1>

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
                    LIMIT 3
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

                        // Check if the logged-in user has liked the post
                        $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
                        if ($user_id) {
                            $confirm_likes->execute([$user_id, $post_id]);
                        }

                        // Truncate content to 2-3 sentences
                        $content = $fetch_posts['content'];
                        $content_preview = implode('. ', array_slice(explode('. ', $content), 0, 2)) . '.';
            ?>
                        <form class="box" method="post">
                            <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                            <div class="post-admin">
                                <i class="fas fa-user"></i>
                                <div>
                                    <a href="author_posts.php?author=<?= $fetch_posts['created_by']; ?>">
                                        <?= $fetch_posts['author_user_name']; ?> <!-- Display username here -->
                                    </a>
                                    <div><?= $fetch_posts['created_at']; ?></div>
                                </div>
                            </div>

                            <?php if ($fetch_posts['image'] != '') { ?>
                                <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                            <?php } ?>

                            <div class="post-title"><?= $fetch_posts['title']; ?></div>
                            <div class="post-content content-150"><?= $content_preview; ?></div>
                            <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">Read More</a>
                            <a href="category.php?category=<?= $fetch_posts['category_id']; ?>" class="post-cat">
                                <i class="fas fa-tag"></i> <span><?= $fetch_posts['category_name']; ?></span>
                            </a>
                            <div class="icons">
                                <a href="view_post.php?post_id=<?= $post_id; ?>">
                                    <i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span>
                                </a>
                                <?php if ($user_id) { ?>
                                    <button type="submit" name="like_post">
                                        <i class="fas fa-heart" style="<?= ($confirm_likes->rowCount() > 0) ? 'color:var(--red);' : ''; ?>"></i>
                                        <span>(<?= $total_post_likes; ?>)</span>
                                    </button>
                                <?php } else { ?>
                                    <a href="login.php" class="like-btn">
                                        <i class="fas fa-heart"></i><span>(<?= $total_post_likes; ?>)</span>
                                    </a>
                                <?php } ?>
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

        <div class="more-btn" style="text-align: center; margin-top:1rem;">
            <a href="posts.php" class="inline-btn">View All Posts</a>
        </div>
    </section>

    <script src="js/script.js"></script>
</body>
</html>