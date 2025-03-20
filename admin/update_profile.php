<?php
include '../components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

// Fetch current profile data
$select_profile = $conn->prepare("SELECT * FROM `accounts` WHERE account_id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){

   $firstname = $_POST['firstname'];
   $firstname = filter_var($firstname, FILTER_SANITIZE_STRING);

   $lastname = $_POST['lastname'];
   $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);

   $middlename = $_POST['middlename'];
   $middlename = filter_var($middlename, FILTER_SANITIZE_STRING);

   $user_name = $_POST['user_name'];
   $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);

   // Check if username or email already exists
   if(!empty($user_name)){
      $select_name = $conn->prepare("SELECT * FROM `accounts` WHERE user_name = ? AND account_id != ?");
      $select_name->execute([$user_name, $admin_id]);
      if($select_name->rowCount() > 0){
         $message[] = 'Username already taken!';
      }
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `accounts` WHERE email = ? AND account_id != ?");
      $select_email->execute([$email, $admin_id]);
      if($select_email->rowCount() > 0){
         $message[] = 'Email already taken!';
      }
   }

   // Update profile if no errors
   if(empty($message)){
      $update_profile = $conn->prepare("UPDATE `accounts` SET firstname = ?, lastname = ?, middlename = ?, user_name = ?, email = ? WHERE account_id = ?");
      $update_profile->execute([$firstname, $lastname, $middlename, $user_name, $email, $admin_id]);
      $message[] = 'Profile updated successfully!';
   }

   // Handle password update
   $old_pass = $_POST['old_pass'];
   $new_pass = $_POST['new_pass'];
   $confirm_pass = $_POST['confirm_pass'];

   if(!empty($old_pass) || !empty($new_pass) || !empty($confirm_pass)){
      if(empty($old_pass) || empty($new_pass) || empty($confirm_pass)){
         $message[] = 'Please fill out all password fields!';
      } else {
         // Verify old password
         if(sha1($old_pass) === $fetch_profile['password']){ // Use sha1() if passwords are stored as sha1 hashes
            if($new_pass === $confirm_pass){
               $hashed_pass = sha1($new_pass); // Use sha1() for new password hashing
               $update_pass = $conn->prepare("UPDATE `accounts` SET password = ? WHERE account_id = ?");
               $update_pass->execute([$hashed_pass, $admin_id]);
               $message[] = 'Password updated successfully!';
            } else {
               $message[] = 'New password and confirm password do not match!';
            }
         } else {
            $message[] = 'Old password is incorrect!';
         }
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
   <title>Update Profile</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- Profile Update Section Starts -->
<section class="form-container">

   <form action="" method="POST">
      <h3>Update Profile</h3>

      <input type="text" name="firstname" maxlength="50" class="box" placeholder="First Name" value="<?= $fetch_profile['firstname']; ?>">
      <input type="text" name="lastname" maxlength="50" class="box" placeholder="Last Name" value="<?= $fetch_profile['lastname']; ?>">
      <input type="text" name="middlename" maxlength="50" class="box" placeholder="Middle Name" value="<?= $fetch_profile['middlename']; ?>">
      <input type="text" name="user_name" maxlength="20" class="box" placeholder="Username" value="<?= $fetch_profile['user_name']; ?>">
      <input type="email" name="email" maxlength="255" class="box" placeholder="Email" value="<?= $fetch_profile['email']; ?>">

      <input type="password" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box">
      <input type="password" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box">

      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>

</section>
<!-- Profile Update Section Ends -->

<!-- Custom JS File -->
<script src="../js/admin_script.js"></script>
</body>
</html>