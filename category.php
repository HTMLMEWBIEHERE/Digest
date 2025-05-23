<?php

include 'components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

if(isset($_SESSION['account_id'])){
   $user_id = $_SESSION['account_id'];
}else{
   $user_id = '';
};

if(isset($_GET['category'])){
   $category_id = (int)$_GET['category']; // Cast to integer to avoid type issues
   // Fetch the category name from the database
   $select_category = $conn->prepare("SELECT name FROM `category` WHERE category_id = ?");
   $select_category->execute([$category_id]);
   if($select_category->rowCount() > 0){
      $fetch_category = $select_category->fetch(PDO::FETCH_ASSOC);
      $category_name = $fetch_category['name'];
   } else {
      $category_name = 'Unknown Category';
   }
}else{
   $category_id = '';
   $category_name = 'Unknown Category';
}

include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Category - <?= htmlspecialchars($category_name); ?></title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->


<section class="posts-container">

   <h1 class="heading">Posts in <?= htmlspecialchars($category_name); ?></h1>

   <div class="box-container">

      <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE category_id = ? AND status = ?");
         $select_posts->execute([$category_id, 'active']);
         echo "<!-- DEBUG: Posts found for category " . htmlspecialchars($category_id) . ": " . $select_posts->rowCount() . " -->";

         // If still no results, check if posts exist at all
         if($select_posts->rowCount() == 0) {
            $check_any_posts = $conn->prepare("SELECT COUNT(*) as total FROM `posts`");
            $check_any_posts->execute();
            $total_posts = $check_any_posts->fetch(PDO::FETCH_ASSOC)['total'];
            echo "<!-- DEBUG: Total posts in database: " . $total_posts . " -->";
         }

         if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
               
            $post_id = $fetch_posts['post_id']; // Assuming your posts table uses post_id as column name

            $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
            $count_post_comments->execute([$post_id]);
            $total_post_comments = $count_post_comments->rowCount(); 

            $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
            $count_post_likes->execute([$post_id]);
            $total_post_likes = $count_post_likes->rowCount();

            $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE account_id = ? AND post_id = ?");
            $confirm_likes->execute([$user_id, $post_id]);
      ?>
      <form class="box" method="post">
         <input type="hidden" name="post_id" value="<?= $post_id; ?>">
         <input type="hidden" name="admin_id" value="<?= $fetch_posts['created_by']; ?>">
         <div class="post-admin">
            <i class="fas fa-user"></i>
            <div>
               <a href="author_posts.php?author=<?= $fetch_posts['author_name']; ?>"><?= $fetch_posts['author_name']; ?></a>
               <div><?= $fetch_posts['date']; ?></div>
            </div>
         </div>
         
         <?php
            if($fetch_posts['image'] != ''){  
         ?>
         <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
         <?php
         }
         ?>
         <div class="post-title"><?= $fetch_posts['title']; ?></div>
         <div class="post-content content-150"><?= $fetch_posts['content']; ?></div>
         <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">read more</a>
         <div class="icons">
            <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span></a>
            <button type="submit" name="like_post"><i class="fas fa-heart" style="<?php if($confirm_likes->rowCount() > 0){ echo 'color:var(--red);'; } ?>  "></i><span>(<?= $total_post_likes; ?>)</span></button>
         </div>
      
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">no posts found for this category!</p>';
      }
      ?>
   </div>

</section>

<!-- custom js file link  -->
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>

</body>
</html>