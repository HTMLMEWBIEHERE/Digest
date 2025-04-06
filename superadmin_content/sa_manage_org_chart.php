<?php
require_once __DIR__ . '/../classes/organization.class.php';

$db = new Database();
$conn = $db->connect();

// Initialize Organization class
$organization = new Organization();

session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
$admin_role = $_SESSION['admin_role'] ?? null;

// Redirect if not logged in or not a superadmin
if(!isset($admin_id) || $admin_role != 'superadmin'){
   header('location:../admin/admin_login.php');
   exit();
}

// Initialize messages array
$message = [];

// Display session message if it exists
if(isset($_SESSION['message'])) {
   $message[] = $_SESSION['message'];
   unset($_SESSION['message']);
}

// Fetch org members
try {
   $org_members = $organization->getOrganizations();
} catch (Exception $e) {
   $message[] = 'Error fetching org members: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Manage Organizational Chart</title>
   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Admin CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/superadmin_header.php' ?>

<section class="org-chart-controls">
   <h1 class="heading">Organizational Chart Management</h1>

   <?php
   if(isset($message) && is_array($message)){
      foreach($message as $msg){
         echo '<div class="message">
            <span>'.$msg.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>';
      }
   }
   ?>
<ul id="categoryList">
    <!-- Existing categories will go here -->
</ul>

<!-- Trigger Buttons -->
<div class="flex-btn">
   <button class="btn" onclick="openModal('addMemberModal')">+ Add Member</button>
   <button class="btn" onclick="openModal('addCategoryModal')">+ Add Category</button>
</div>

   <div id="formContainer"></div>
</section>

<section class="show-org-members">
   <h1 class="heading">Organizational Members</h1>

   <div class="box-container">
      <?php if(count($org_members) > 0): ?>
         <?php foreach($org_members as $member): ?>
            <div class="box">
               <h3><?= htmlspecialchars($member['name']); ?></h3>
               <p><?= htmlspecialchars($member['position']); ?></p>
               <p>Category: <?= htmlspecialchars($member['category_name'] ?? 'Uncategorized'); ?></p>
               <?php if(!empty($member['image'])): ?>
                  <img src="../<?= htmlspecialchars($member['image']); ?>" alt="<?= htmlspecialchars($member['name']); ?>">
               <?php endif; ?>
               <p>Appointed: <?= $member['date_appointed']; ?>
               </p>
               <div class="flex-btn">
                  <form action="" method="post" style="display:inline;" onsubmit="return confirm('Remove this member?');">
                     <input type="hidden" name="org_id" value="<?= $member['org_id']; ?>">
                     <button type="submit" name="delete_member" class="delete-btn">Remove</button>
                  </form>
               </div>
            </div>
         <?php endforeach; ?>
      <?php else: ?>
         <p class="empty">No org members found!</p>
      <?php endif; ?>
   </div>
</section>

<!-- Include Modals -->
<?php
include('../modals/add_member_modal.php');
include('../modals/add_category_modal.php');
?>

<script src="../js/org_chart.js"></script>
</body>
</html>
