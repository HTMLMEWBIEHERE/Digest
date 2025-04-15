<?php
include '../components/connect.php';

$db = new Database();
$conn = $db->connect();

session_start();

$message = [];

// Check if this is a registration request
if(isset($_POST['register_admin']) && isset($_POST['secret_key'])) {
   // SECURITY: Check secret key - this prevents unauthorized admin creation
   // Change this to a strong, unique value
   $valid_secret_key = "TDG_SECRET_KEY_2025";
   
   if($_POST['secret_key'] !== $valid_secret_key) {
      $message[] = 'Invalid secret key. Admin registration not allowed.';
   } else {
      // Process admin registration
      $firstname = trim($_POST['firstname']);
      $firstname = filter_var($firstname, FILTER_SANITIZE_STRING);
      
      $lastname = trim($_POST['lastname']);
      $lastname = filter_var($lastname, FILTER_SANITIZE_STRING);
      
      // Add middlename handling
      $middlename = trim($_POST['middlename'] ?? '');
      $middlename = filter_var($middlename, FILTER_SANITIZE_STRING);
      
      $username = trim($_POST['username']);
      $username = filter_var($username, FILTER_SANITIZE_STRING);
      $username = str_replace(' ', '', $username);
      
      $email = trim($_POST['email']);
      $email = filter_var($email, FILTER_SANITIZE_EMAIL);
      
      $password = $_POST['password'];
      $cpassword = $_POST['cpassword'];
      
      $role = $_POST['role']; // superadmin or subadmin
      
      // Validate role
      if($role !== 'superadmin' && $role !== 'subadmin') {
         $message[] = 'Invalid role selection.';
      }
      // Validate passwords match
      else if($password !== $cpassword) {
         $message[] = 'Passwords do not match.';
      } 
      // Check if username or email already exists
      else {
         $check_account = $conn->prepare("SELECT * FROM `accounts` WHERE user_name = ? OR email = ?");
         $check_account->execute([$username, $email]);
         
         if($check_account->rowCount() > 0) {
            $message[] = 'Username or email already exists!';
         } else {
            // All validations passed, create the account
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert = $conn->prepare("INSERT INTO `accounts` 
               (firstname, lastname, middlename, user_name, email, password, role) 
               VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $result = $insert->execute([
               $firstname, 
               $lastname, 
               $middlename, // Use middlename variable instead of empty string
               $username, 
               $email, 
               $hashed_password, 
               $role
            ]);
            
            if($result) {
               $message[] = 'Admin account created successfully!';
            } else {
               $message[] = 'Failed to create admin account.';
            }
         }
      }
   }
}

// Normal login process
if(isset($_POST['submit'])){
   // Modern input sanitization
   $email = trim($_POST['email']);
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $password = $_POST['pass'];
   
   // First check if the email exists
   $select_admin = $conn->prepare("SELECT * FROM `accounts` WHERE email = ? AND (role = 'superadmin' OR role = 'subadmin')");
   $select_admin->execute([$email]);
   
   if($select_admin->rowCount() > 0){
      $admin = $select_admin->fetch(PDO::FETCH_ASSOC);
      
      // COMPREHENSIVE APPROACH: Check multiple formats to help with transition
      
      // Check if using new password_hash format
      if(password_verify($password, $admin['password'])) {
         // Password is correct using the new format
         $_SESSION['admin_id'] = $admin['account_id'];
         $_SESSION['email'] = $admin['email'];
         $_SESSION['admin_role'] = $admin['role'];
         
         // Redirect based on admin role
         if($admin['role'] === 'superadmin') {
            header('location:../super_admin/superadmin_dashboard.php');
         } else {
            header('location:dashboard.php');
         }
         exit();
      } 
      // Check if using old sha1 format
      else if(sha1($password) === $admin['password']) {
         // Password is correct using old format - update to new format
         $new_hash = password_hash($password, PASSWORD_DEFAULT);
         $update_password = $conn->prepare("UPDATE `accounts` SET password = ? WHERE account_id = ?");
         $update_password->execute([$new_hash, $admin['account_id']]);
         
         // Set session and redirect
         $_SESSION['admin_id'] = $admin['account_id'];
         $_SESSION['email'] = $admin['email'];
         $_SESSION['admin_role'] = $admin['role'];
         
         // Redirect based on admin role
         if($admin['role'] === 'superadmin') {
            header('location:../super_admin/superadmin_dashboard.php');
         } else {
            header('location:dashboard.php');
         }
         exit();
      }
      // Check for MD5 (another common legacy format)
      else if(md5($password) === $admin['password']) {
         // Upgrade from MD5 to secure hash
         $new_hash = password_hash($password, PASSWORD_DEFAULT);
         $update_password = $conn->prepare("UPDATE `accounts` SET password = ? WHERE account_id = ?");
         $update_password->execute([$new_hash, $admin['account_id']]);
         
         $_SESSION['admin_id'] = $admin['account_id'];
         $_SESSION['email'] = $admin['email'];
         $_SESSION['admin_role'] = $admin['role'];
         
         // Redirect based on admin role
         if($admin['role'] === 'superadmin') {
            header('location:../super_admin/superadmin_dashboard.php');
         } else {
            header('location:dashboard.php');
         }
         exit();
      }
      else {
         $message[] = 'Incorrect password!';
      }
   } else {
      $message[] = 'Admin account not found with this email!';
   }
}

// Toggle between login and registration forms
$show_registration = isset($_GET['register']) && $_GET['register'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $show_registration ? 'Register Admin' : 'Admin Login'; ?></title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body class="login-body">

<?php
if(isset($message) && !empty($message)){
   foreach($message as $msg){
      echo '
      <div class="auth-message">
         <span>'.$msg.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<?php if($show_registration): ?>
<!-- Back to Login Button - For Registration Form -->
<div class="auth-flex-btn">
   <a href="admin_login.php" class="auth-option-btn">Back to Login</a>
</div>

<!-- Admin Registration Form Section Starts -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Register New Admin</h3>
      
      <input type="text" name="firstname" required placeholder="First name" class="box">
      <input type="text" name="lastname" required placeholder="Last name" class="box">
      <input type="text" name="middlename" placeholder="Middle name (optional)" class="box">
      <input type="text" name="username" required placeholder="Username" class="box" 
             oninput="this.value = this.value.replace(/\s/g, '')" 
             pattern="[^\s]+" title="Username cannot contain spaces">
      <input type="email" name="email" required placeholder="Email" class="box">
      <input type="password" name="password" required placeholder="Password" class="box">
      <input type="password" name="cpassword" required placeholder="Confirm password" class="box">
      
      <select name="role" class="box" required>
         <option value="" disabled selected>Select Role</option>
         <option value="superadmin">Super Admin</option>
         <option value="subadmin">Sub Admin</option>
      </select>
      
      <input type="password" name="secret_key" required placeholder="Secret Key" class="box">
      <input type="submit" value="Register Admin" name="register_admin" class="btn">
   </form>
</section>
<!-- Admin Registration Form Section Ends -->

<?php else: ?>
<!-- Admin Login Form Section Starts -->
<section class="form-container">
   <form action="" method="POST">
      <h3>Admin Login</h3>
      <input type="email" name="email" maxlength="255" required placeholder="Enter your email" class="box">
      <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box">
      <input type="submit" value="Login Now" name="submit" class="btn">
   </form>
   
   <!-- Register Admin Button - Below Login Form -->
   <div class="auth-flex-btn">
      <a href="admin_login.php?register=true" class="auth-option-btn">Register Admin</a>
   </div>
</section>
<!-- Admin Login Form Section Ends -->
<?php endif; ?>

</body>
</html>