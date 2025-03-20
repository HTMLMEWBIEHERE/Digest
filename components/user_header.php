<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The University Digest</title>
    <link rel="stylesheet" href="../css/userheader.css">
    <!-- Updated Material Icons Import -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <div class="logo-container">
        <a href="../home.php">
            <img src="../imgs/logo.png" alt="The University Digest Logo">
            <div class="logo"><h1>The University Digest</h1></div>
        </a>
    </div>
    <div class="search-box">
        <input type="text" name="search" id="search" placeholder="Search">
        <button type="submit"><span class="material-symbols-outlined">search</span></button>
        <div id="search-results"></div>
    </div>

    <ul class="nav-menu">
        <li><a href="../home.php">Home</a></li>
        <li><a href="../all_category.php">Categories</a></li>
        <li><a href="../authors.php">Authors</a></li>
        <li><a href="#">About Us</a></li>
    </ul>
</header>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/script.js"></script>

</body>
</html>