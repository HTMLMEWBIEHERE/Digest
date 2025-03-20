<?php
include 'components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_GET['author'])) {
    $author_id = $_GET['author']; // Use author ID instead of name
} else {
    $author_id = '';
}

include 'components/like_post.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Posts</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Header Section Starts -->
<?php include 'components/user_header.php'; ?>
<!-- Header Section Ends -->

<section class="posts-container">
    <div class="box-container">
        <?php
        // Fetch posts created by the author
        $select_posts = $conn->prepare("
            SELECT posts.*, accounts.user_name AS author_name 
            FROM `posts` 
            JOIN `accounts` ON posts.created_by = accounts.account_id 
            WHERE posts.created_by = ? AND posts.status = 'published'
        ");
        $select_posts->execute([$author_id]);

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
                $confirm_likes->execute([$user_id, $post_id]);

                // Truncate content to 2-3 sentences
                $content = $fetch_posts['content'];
                $content_preview = implode('. ', array_slice(explode('. ', $content), 0, 2)) . '.';
        ?>
                <form class="box" method="post">
                    <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                    <input type="hidden" name="admin_id" value="<?= $fetch_posts['created_by']; ?>">
                    <div class="post-admin">
                        <i class="fas fa-user"></i>
                        <div>
                            <a href="author_posts.php?author=<?= $fetch_posts['created_by']; ?>"><?= $fetch_posts['author_name']; ?></a>
                            <div><?= $fetch_posts['created_at']; ?></div>
                        </div>
                    </div>

                    <?php if ($fetch_posts['image'] != ''): ?>
                        <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                    <?php endif; ?>

                    <div class="post-title"><?= $fetch_posts['title']; ?></div>
                    <div class="post-content content-150"><?= $content_preview; ?></div>
                    <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">read more</a>
                    <div class="icons">
                        <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span></a>
                        <button type="submit" name="like_post">
                            <i class="fas fa-heart" style="<?= $confirm_likes->rowCount() > 0 ? 'color:var(--red);' : ''; ?>"></i>
                            <span>(<?= $total_post_likes; ?>)</span>
                        </button>
                    </div>
                </form>
        <?php
            }
        } else {
            echo '<p class="empty">No posts found for this author!</p>';
        }
        ?>
    </div>
</section>

<!-- Custom JS File -->
<script src="js/script.js"></script>
</body>
</html>