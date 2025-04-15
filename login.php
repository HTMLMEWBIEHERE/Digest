<?php
// filepath: c:\xampp\htdocs\TDG-PROJECT\login.php
include 'components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

// Redirect if already logged in
if(isset($_SESSION['account_id'])){
   header('location:home.php');
   exit();
}

$message = [];

if(isset($_POST['submit'])){
   // Get and sanitize input
   $email = trim($_POST['email']);
   $password = $_POST['password'];
   
   // Validate input
   if(empty($email)){
      $message[] = 'Email is required!';
   }
   
   if(empty($password)){
      $message[] = 'Password is required!';
   }
   
   // If no validation errors, attempt login
   if(empty($message)){
      // Query accounts table for the user with this email
      $select_user = $conn->prepare("SELECT * FROM `accounts` WHERE email = ?");
      $select_user->execute([$email]);
      
      if($select_user->rowCount() > 0){
         $user = $select_user->fetch(PDO::FETCH_ASSOC);
         
         // Verify password using secure method
         if(password_verify($password, $user['password'])){
            // Set session variables
            $_SESSION['account_id'] = $user['account_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect based on role
            if($user['role'] == 'superadmin' || $user['role'] == 'subadmin'){
               header('location:admin/dashboard.php');
            } else {
               header('location:home.php');
            }
            exit();
         } else {
            $message[] = 'Incorrect password!';
         }
      } else {
         $message[] = 'Email not found!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - The University Digest</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">
   <form action="" method="post">
      <h3>Login to Your Account</h3>
      
      <?php if(!empty($message)): ?>
      <div class="message">
         <?php foreach($message as $msg): ?>
            <p><?= $msg; ?></p>
         <?php endforeach; ?>
      </div>
      <?php endif; ?>
      
      <input type="email" name="email" placeholder="Enter your email" class="box" value="<?= $email ?? ''; ?>">
      <input type="password" name="password" placeholder="Enter your password" class="box">
      <input type="submit" value="Login Now" name="submit" class="btn">
      <p>Don't have an account? <a href="register.php">Register now</a></p>
   </form>
</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>