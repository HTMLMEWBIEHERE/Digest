<?php

include 'components/connect.php';

$db = new Database();
$conn = $db->connect();


session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categories</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="categories">

   <h1 class="heading">Categories</h1>

   <div class="box-container">
      <?php
         $select_categories = $conn->prepare("SELECT * FROM `category`");
         $select_categories->execute();
         if($select_categories->rowCount() > 0){
            $index = 1;
            while($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)){
               $category_id = $fetch_category['category_id'];
               $category_name = $fetch_category['name'];
      ?>
      <div class="box"><span><?= str_pad($index, 2, '0', STR_PAD_LEFT); ?></span><a href="category.php?category=<?= $category_id; ?>"><?= htmlspecialchars($category_name); ?></a></div>
      <?php
               $index++;
            }
         } else {
            echo '<p class="empty">No categories found!</p>';
         }
      ?>
   </div>

</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>