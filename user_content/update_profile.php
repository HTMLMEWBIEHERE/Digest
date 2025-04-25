<?php

include '../components/connect.php';

$db = new Database();
$conn = $db->connect();

// Remove redundant session_start since it's likely already called in connect.php
// If session_start() is not in connect.php, uncomment the next line
// if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Use account_id instead of user_id to match your other files
if(isset($_SESSION['account_id'])){
   $account_id = $_SESSION['account_id'];
}else{
   $account_id = '';
   header('location:home.php');
   exit;
};

if(isset($_POST['submit'])){
   // For firstname and lastname update
   $firstname = $_POST['firstname'] ?? '';
   $firstname = filter_var($firstname, FILTER_SANITIZE_STRING);
   
   $lastname = $_POST['lastname'] ?? '';
   $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);

   $email = $_POST['email'] ?? '';
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   
   // Update firstname if provided
   if(!empty($firstname)){
      $update_firstname = $conn->prepare("UPDATE `accounts` SET firstname = ? WHERE account_id = ?");
      $update_firstname->execute([$firstname, $account_id]);
   }
   
   // Update lastname if provided
   if(!empty($lastname)){
      $update_lastname = $conn->prepare("UPDATE `accounts` SET lastname = ? WHERE account_id = ?");
      $update_lastname->execute([$lastname, $account_id]);
   }

   // Update email if provided
   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `accounts` WHERE email = ? AND account_id != ?");
      $select_email->execute([$email, $account_id]);
      if($select_email->rowCount() > 0){
         $message[] = 'Email already taken!';
      }else{
         $update_email = $conn->prepare("UPDATE `accounts` SET email = ? WHERE account_id = ?");
         $update_email->execute([$email, $account_id]);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709'; // SHA1 of empty string
   $select_prev_pass = $conn->prepare("SELECT password FROM `accounts` WHERE account_id = ?");
   $select_prev_pass->execute([$account_id]);
   $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'Old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'Confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `accounts` SET password = ? WHERE account_id = ?");
            $update_pass->execute([$confirm_pass, $account_id]);
            $message[] = 'Password updated successfully!';
         }else{
            $message[] = 'Please enter a new password!';
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

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/userheader.css">
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
   <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->

<section class="form-container">
   <form action="" method="post">
      <h3>Update Profile</h3>
      <?php
         // Display any messages
         if(isset($message)){
            foreach($message as $msg){
               echo '<div class="message">'.$msg.'</div>';
            }
         }
      ?>
      <input type="text" name="firstname" placeholder="<?= $fetch_profile['firstname'] ?? ''; ?>" class="box" maxlength="50">
      <input type="text" name="lastname" placeholder="<?= $fetch_profile['lastname'] ?? ''; ?>" class="box" maxlength="50">
      <input type="email" name="email" placeholder="<?= $fetch_profile['email'] ?? ''; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="old_pass" placeholder="Enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="Confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>