<?php

include '../components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">

   <div class="box">
      <h3>welcome!</h3>
      <p><?= $fetch_profile['firstname'] . ' ' . $fetch_profile['lastname']; ?></p>
      <a href="update_profile.php" class="btn">update profile</a>
   </div>

   <div class="box">
      <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE created_by = ? AND status = 'published'");
         $select_posts->execute([$admin_id]);
         $numbers_of_posts = $select_posts->rowCount();
      ?>
      <h3><?= $numbers_of_posts; ?></h3>
      <p>published posts</p>
      <a href="../admin_content/add_posts.php" class="btn">add new post</a>
   </div>

   <div class="box">
      <?php
      $select_tejido = $conn->prepare("SELECT * FROM `tejido` WHERE created_by = ?");
      $select_tejido->execute([$admin_id]);
      $numbers_of_tejido = $select_tejido->rowCount();
      ?>
      <h3><?= $numbers_of_tejido; ?></h3>
      <p>tejido added</p>
      <a href="../admin_content/add_tejido.php" class="btn">Add tejido</a>
   </div>

   <div class="box">
      <?php
         $select_deactive_posts = $conn->prepare("SELECT * FROM `posts` WHERE created_by = ? AND status = 'draft'");
         $select_deactive_posts->execute([$admin_id]);
         $numbers_of_deactive_posts = $select_deactive_posts->rowCount();
      ?>
      <h3><?= $numbers_of_deactive_posts; ?></h3>
      <p>draft posts</p>
      <a href="../admin_content/view_posts.php" class="btn">See drafts</a>
   </div>

   <div class="box">
      <?php
         $select_users = $conn->prepare("SELECT * FROM `accounts` WHERE role = 'user'");
         $select_users->execute();
         $numbers_of_users = $select_users->rowCount();
      ?>
      <h3><?= $numbers_of_users; ?></h3>
      <p>users account</p>
      <a href="./user_accounts_management.php" class="btn">see users</a>
   </div>

   <div class="box">
      <?php
         $select_admins = $conn->prepare("SELECT * FROM `accounts` WHERE role IN ('superadmin', 'subadmin')");
         $select_admins->execute();
         $numbers_of_admins = $select_admins->rowCount();
      ?>
      <h3><?= $numbers_of_admins; ?></h3>
      <p>Articles</p>
      <a href="admin_accounts.php" class="btn">Add Articles</a>
   </div>
   
   <div class="box">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE commented_by = ?");
         $select_comments->execute([$admin_id]);
         $numbers_of_comments = $select_comments->rowCount();
      ?>
      <h3><?= $numbers_of_comments; ?></h3>
      <p>comments added</p>
      <a href="../admin_content/comments.php" class="btn">see comments</a>
   </div>

   <div class="box">
      <?php
         $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE account_id = ?");
         $select_likes->execute([$admin_id]);
         $numbers_of_likes = $select_likes->rowCount();
      ?>
      <h3><?= $numbers_of_likes; ?></h3>
      <p>total likes</p>
      <a href="../admin_content/total_likes.php" class="btn">See Total Likes</a>
   </div>
   </div>

</section>
<script src="../js/admin_script.js"></script>

</body>
</html>