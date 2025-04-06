<?php
require_once 'classes/organization.class.php';
include 'components/user_header.php';
$organization = new Organization();
$organizations = $organization->getOrganizations() ?: [];

// Separate organizations by their roles and categories
$adviser = null;
$editorialBoard = [];
$chiefEditors = [];
$otherEditorialMembers = [];


$writerMembers = [];
$writerHeadMembers = [];
$writerOtherMembers = [];

$photoJournalistMembers = [];
$photoJournalistHeadMembers = [];
$photoJournalistOtherMembers = [];


$cartoonistMembers = [];
$cartoonistHeadMembers = [];
$cartoonistOtherMembers = [];


$layoutArtistMembers = [];
$layoutArtistHeadMembers = [];
$layoutArtistOtherMembers = [];


foreach ($organizations as $org) {
    $position = strtolower($org['position']);
    if ($position === 'adviser') {
        $adviser = $org;
    } elseif (in_array($position, ['editor-in-chief', 'associate editor'])) {
        $chiefEditors[] = $org;
    } elseif (strtolower($org['category']) === 'editorial board & staff') {
        $otherEditorialMembers[] = $org;
    }
}




foreach ($organizations as $org) {
    $category = strtolower($org['category']);
    $position = strtolower($org['position']);
    
    if ($category === 'writers') {
        if (in_array($position, ['head', 'associate head'])) {
            $writerHeadMembers[] = $org;
        } else {
            $writerOtherMembers[] = $org;
        }
    }
}


foreach ($organizations as $org) {
    $category = strtolower($org['category']);
    $position = strtolower($org['position']);
    
    if ($category === 'photo journalists') {
        if (in_array($position, ['head', 'associate head'])) {
            $photoJournalistHeadMembers[] = $org;
        } else {
            $photoJournalistOtherMembers[] = $org;
        }
    }
}

foreach ($organizations as $org) {
    $category = strtolower($org['category']);
    $position = strtolower($org['position']);
    
    if ($category === 'cartoonists') {
        if (in_array($position, ['head', 'associate head'])) {
            $cartoonistHeadMembers[] = $org;
        } else {
            $cartoonistOtherMembers[] = $org;
        }
    }
}



foreach ($organizations as $org) {
    $category = strtolower($org['category']);
    $position = strtolower($org['position']);
    
    if ($category === 'layout artists') {
        if (in_array($position, ['head', 'associate head'])) {
            $layoutArtistHeadMembers[] = $org;
        } else {
            $layoutArtistOtherMembers[] = $org;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/aboutus.css">
</head>
<body>
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

    <div class="main">
        <!-- Adviser Section -->
        <div class="adviser-container">
            <h1>EDITORIAL BOARD & STAFF</h1>
            <div class="editorial-cards">
                <?php if ($adviser): ?>
                    <div class="card">
                        <div class="card-image">
                            <?php if($adviser['image'] != ''){ ?>
                                <img src="uploads/members/<?= basename($adviser['image']); ?>" alt="Adviser">
                            <?php } else { ?>
                                <img src="./imgs/member.jpg" alt="Default Adviser Image">
                            <?php } ?>
                        </div>
                        <div class="card-content">
                            <h2><?= htmlspecialchars($adviser['name']) ?></h2>
                            <p><?= htmlspecialchars($adviser['position']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <br>
        <!-- Editor-in-Chief and Associate Editor Section -->
        <div class="editorial-container">
            <div class="editorial-cards">
                <?php foreach ($chiefEditors as $member): ?>
                    <div class="card">
                        <div class="card-image">
                            <?php if($member['image'] != ''){ ?>
                                <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                            <?php } else { ?>
                                <img src="./imgs/member.jpg" alt="Default Member Image">
                            <?php } ?>
                        </div>
                        <div class="card-content">
                            <h2><?= htmlspecialchars($member['name']) ?></h2>
                            <p><?= htmlspecialchars($member['position']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Other Editorial Members in 4 Columns -->
            <div class="editorial-container2">
                <?php foreach ($otherEditorialMembers as $member): ?>
                    <div class="cards">
                        <div class="card-image">
                            <?php if($member['image'] != ''){ ?>
                                <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                            <?php } else { ?>
                                <img src="./imgs/member.jpg" alt="Default Member Image">
                            <?php } ?>
                        </div>
                        <div class="card-content">
                            <h2><?= htmlspecialchars($member['name']) ?></h2>
                            <p><?= htmlspecialchars($member['position']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Writers Section -->
<div class="writers-container">
    <h1>WRITERS</h1>
    
    <!-- Head and Associate Head Cards -->
    <div class="editorial-container">
        <div class="editorial-cards">
            <?php foreach ($writerHeadMembers as $member): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Other Writer Members in Grid -->
        <div class="editorial-container2">
            <?php foreach ($writerOtherMembers as $member): ?>
                <div class="cards">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
        

<!-- Photo Journalists Section -->
<div class="writers-container">
    <h1>PHOTO JOURNALISTS</h1>
    
    <!-- Head and Associate Head Cards -->
    <div class="editorial-container">
        <div class="editorial-cards">
            <?php foreach ($photoJournalistHeadMembers as $member): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Other Photo Journalist Members in Grid -->
        <div class="editorial-container2">
            <?php foreach ($photoJournalistOtherMembers as $member): ?>
                <div class="cards">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<!-- Cartoonists Section -->
<div class="writers-container">
    <h1>CARTOONISTS</h1>
    
    <!-- Head and Associate Head Cards -->
    <div class="editorial-container">
        <div class="editorial-cards">
            <?php foreach ($cartoonistHeadMembers as $member): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Other Cartoonist Members in Grid -->
        <div class="editorial-container2">
            <?php foreach ($cartoonistOtherMembers as $member): ?>
                <div class="cards">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Layout Artists Section -->
<div class="writers-container">
    <h1>LAYOUT ARTISTS</h1>
    
    <!-- Head and Associate Head Cards -->
    <div class="editorial-container">
        <div class="editorial-cards">
            <?php foreach ($layoutArtistHeadMembers as $member): ?>
                <div class="card">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Other Layout Artist Members in Grid -->
        <div class="editorial-container2">
            <?php foreach ($layoutArtistOtherMembers as $member): ?>
                <div class="cards">
                    <div class="card-image">
                        <?php if($member['image'] != ''){ ?>
                            <img src="uploads/members/<?= basename($member['image']); ?>" alt="<?= htmlspecialchars($member['position']) ?>">
                        <?php } else { ?>
                            <img src="./imgs/member.jpg" alt="Default Member Image">
                        <?php } ?>
                    </div>
                    <div class="card-content">
                        <h2><?= htmlspecialchars($member['name']) ?></h2>
                        <p><?= htmlspecialchars($member['position']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
        <!-- Rest of the sections remain unchanged -->
        <!-- ... -->
    </div>  
    <?php include 'footer.php'; ?>
</body>
</html>
