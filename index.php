<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

if (isset($_GET['update'])) {
    $profileUpdate = "Your profile has been updated";
}




$id = $_SESSION["loggedInUser"];
$stmt = $pdo->prepare("SELECT * FROM userdb WHERE userid= ?;");
$stmt->execute([$_SESSION['loggedInUser']]);
$loggedInUser = $stmt->fetch();

$species = $loggedInUser['species'];
if ($loggedInUser['species'] === "cat") {
    $species = "human";
} else {
    $species = "cat";
}

$matching = '<div class="startItem"><a href="matching.php"><h1>Matching</h1><img src="matching.jpg" alt="matching" srcset=""></a></div>';
$likes = '<div class="startItem"><h1><a>' . $loggedInUser['friends'] . ' likes</a></h1><img src="matching.jpg" alt="likes" srcset=""></div>';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body id="feed">
    <?php if (isset($_GET['update'])) {
        echo '<p>' . $profileUpdate . '</p>';
    } ?>
    <div class="startWrapper">
        <div class="startItem">
            <a href="profile.php">
                <h1>Edit Profile</h1>
                <img src="profile.jpg" alt="profile">
            </a>
        </div>

        <?php if ($loggedInUser['species'] === "human") {
            echo $matching;
        } else {
            echo $likes;
        }
        ?>
        <div class="startItem">
            <a href="messages/messages.php">
                <h1>Messages</h1>
                <img src="messages.jpg" alt="messages">
            </a>
        </div>
    </div>
</body>

</html>