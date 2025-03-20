<?php
include '../components/connect.php';

$db = new Database();
$conn = $db->connect();


session_start();

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   // Fetch admin details including role
   $select_admin = $conn->prepare("SELECT * FROM `accounts` WHERE email = ? AND password = ? AND role = 'subadmin'");
   $select_admin->execute([$email, $pass]);
   
   if($select_admin->rowCount() > 0){
      $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      $_SESSION['admin_id'] = $fetch_admin['account_id'];
      $_SESSION['email'] = $fetch_admin['email']; // Optional: Store email in session
      $_SESSION['admin_role'] = $fetch_admin['role']; // Store role in session
      header('location:dashboard.php');
   } else {
      $message[] = 'Incorrect email or password!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="padding-left: 0 !important;">

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- Admin Login Form Section Starts -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Login Now</h3>
      <input type="email" name="email" maxlength="255" required placeholder="Enter your email" class="box">
      <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box">
      <input type="submit" value="Login Now" name="submit" class="btn">
   </form>
</section>
<!-- Admin Login Form Section Ends -->

</body>
</html>