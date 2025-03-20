<?php
require_once __DIR__ . '/../components/connect.php';

$db = new Database();
$conn = $db->connect();


session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_role = $_SESSION['admin_role'] ?? null;

// Redirect if not logged in or not a subadmin
if(!isset($admin_id) || $admin_role != 'subadmin'){
   header('location:../admin_content/admin_login.php');
   exit();
}

// Add tejido post
if(isset($_POST['add_tejido'])){
   $title = $_POST['title'];
   $description = $_POST['description'];
   $category_id = $_POST['category_id'];
   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/' . $image;

   // Validate inputs
   if(empty($title) || empty($description) || empty($category_id) || empty($image)){
      $message[] = 'Please fill out all fields!';
   } else {
      // Insert tejido post into the database
      $insert_tejido = $conn->prepare("INSERT INTO `tejido` (title, description, category_id, img, created_by) VALUES (?, ?, ?, ?, ?)");
      $insert_tejido->execute([$title, $description, $category_id, $image, $admin_id]);

      if($insert_tejido){
         move_uploaded_file($image_tmp_name, $image_folder);
         $message[] = 'Tejido post added successfully!';
      } else {
         $message[] = 'Failed to add tejido post!';
      }
   }
}

// Fetch all tejido posts created by the logged-in subadmin
$select_tejido = $conn->prepare("SELECT * FROM `tejido` WHERE created_by = ?");
$select_tejido->execute([$admin_id]);
$tejido_posts = $select_tejido->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Tejido</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<section class="add-tejido">
   <h1 class="heading">Add Tejido</h1>

   <?php
   if(isset($message) && is_array($message)){
      foreach($message as $msg){
         echo '
         <div class="message">
            <span>'.$msg.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
   ?>

   <!-- Add Tejido Form -->
   <form action="" method="POST" enctype="multipart/form-data">
      <div class="input-box">
         <input type="text" name="title" maxlength="255" required placeholder="Enter title" class="box">
      </div>
      <div class="input-box">
         <textarea name="description" maxlength="1000" required placeholder="Enter description" class="box"></textarea>
      </div>
      <div class="input-box">
         <select name="category_id" required class="box">
            <option value="" disabled selected>Select category</option>
            <?php
            // Fetch categories from the database
            $select_categories = $conn->prepare("SELECT * FROM `category`");
            $select_categories->execute();
            $categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);
            foreach($categories as $category){
               echo '<option value="'.$category['category_id'].'">'.$category['name'].'</option>';
            }
            ?>
         </select>
      </div>
      <div class="input-box">
         <input type="file" name="image" required accept="image/*" class="box">
      </div>
      <input type="submit" value="Add Tejido" name="add_tejido" class="btn">
   </form>
</section>

<section class="tejido-posts">
   <h1 class="heading">Your Tejido Posts</h1>

   <div class="box-container">
      <?php if(count($tejido_posts) > 0): ?>
         <?php foreach($tejido_posts as $post): ?>
            <div class="box">
               <p>Title: <span><?= $post['title']; ?></span></p>
               <p>Description: <span><?= $post['description']; ?></span></p>
               <p>Category: 
                  <span>
                     <?php
                     // Fetch category name
                     $select_category = $conn->prepare("SELECT name FROM `category` WHERE category_id = ?");
                     $select_category->execute([$post['category_id']]);
                     $category = $select_category->fetch(PDO::FETCH_ASSOC);
                     echo $category['name'];
                     ?>
                  </span>
               </p>
               <p>Image: <img src="../uploaded_img/<?= $post['img']; ?>" alt="<?= $post['title']; ?>" width="100"></p>
               <p>Created At: <span><?= $post['created_at']; ?></span></p>
               <p>Updated At: <span><?= $post['updated_at']; ?></span></p>
               <a href="edit_tejido.php?id=<?= $post['tejido_id']; ?>" class="btn">Edit</a>
               <a href="delete_tejido.php?id=<?= $post['tejido_id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No tejido posts found!</p>
      <?php endif; ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>