<?php
include 'components/connect.php';

$db = new Database();
$conn = $db->connect();

// Start session if needed
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - The University Digest</title>
    <!-- Fixed CSS path - remove the leading ../ -->
    <link rel="stylesheet" href="css/about_us.css">
    <!-- Include your main stylesheet too -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>
    
    <div class="main">
        <div class="card-2">
            <div class="card-content">
                <h2 style="color:#4F0003;">About The University Digest</h2>
                <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="logo-image">
                <img src="./imgs/logo_trans.png" alt="Sample Image">
            </div>
        </div>
        
        <div class="adviser-container">
            <h1>EDITORIAL BOARD & STAFF</h1>
            <div class="editorial-cards">
                <div class="card">
                    <div class="card-image">
                        <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                    </div>
                    <div class="card-content">
                        <h2>Lea S. Usman</h2>
                        <p>Adviser</p>
                    </div>
                </div>
            </div>
        </div>
        
        <br>
        
        <div class="editorial-container">
            <div class="editorial-cards">
                <!-- Editorial staff cards -->
                <!-- Fixed the HTML structure here -->
                <div class="card">
                    <div class="card-image">
                        <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                    </div>
                    <div class="card-content">
                        <h2>Jane Doe</h2>
                        <p>Editor-in-Chief</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-image">
                        <img src="./imgs/member.jpg" alt="Managing Editor">
                    </div>
                    <div class="card-content">
                        <h2>Jane Doe</h2>
                        <p>Associate Editor</p>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="editorial-container2">
           <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Managing Editor For Administration</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Managing Editor For Finance</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Managing Editor For Advocacy For Visual Works</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Managing Editor For Advocacy For Written Works</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>News Editor</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Feature Editor</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Literary Editor</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Literary Editor</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Auditor</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Office Managers</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Office Managers</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Office Managers</p>
                </div>
            </div>
        </div>
        <br>


        <div class="writers-container">
        <h1>WRITERS</h1>
            <div class="editorial-cards">
            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Head</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Managing Editor">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Associate Head</p>
                </div>
            </div>

            <div class="editorial-container2">
           <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>
            </div>
        </div>

        <div class="writers-container">
        <h1>PHOTO JOURNALISTS</h1>
            <div class="editorial-cards">
            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Head</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Managing Editor">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Associate Head</p>
                </div>
            </div>

            <div class="editorial-container2">
           <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>
            </div>
        </div>

        <div class="writers-container">
        <h1>CARTOONISTS</h1>
            <div class="editorial-cards">
            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Head</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Managing Editor">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Associate Head</p>
                </div>
            </div>

            <div class="editorial-container2">
           <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>
            </div>
        </div>

        <div class="writers-container">
        <h1>LAYOUT ARTISTS</h1>
            <div class="editorial-cards">
            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Head</p>
                </div>
            </div>

            <div class="card">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Managing Editor">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Associate Head</p>
                </div>
            </div>

            <div class="editorial-container2">
           <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>

            <div class="cards">
                <div class="card-image">
                    <img src="./imgs/member.jpg" alt="Editor-in-Chief">
                </div>
                <div class="card-content">
                    <h2>Jane Doe</h2>
                    <p>Member</p>
                </div>
            </div>
            </div>
        </div>
    </div>  
    <!-- Add space before footer -->
    <div style="margin-bottom: 50px;"></div>
    
    <!-- Fix the footer path -->
    <?php include 'components/footer.php'; ?>
    
    <!-- Add your scripts -->
    <script src="js/script.js"></script>
</body>
</html>