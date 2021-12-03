<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

include '../db.php';

$messageId = $_GET["messageid"];
$stmt = $pdo->prepare("SELECT * FROM messagedb WHERE messageid= ?;");
$stmt->execute([$messageId]);
$messageData = $stmt->fetch();

$stmt = $pdo->prepare("SELECT name FROM userdb WHERE userid= ?;");
$stmt->execute([$messageData['senderid']]);
$senderName = $stmt->fetch();

$stmt = $pdo->prepare("SELECT name FROM userdb WHERE userid= ?;");
$stmt->execute([$messageData['userid']]);
$receiverName = $stmt->fetch();

$title = $messageData['title'];
$message = $messageData['message'];


if ($_SESSION['loggedInUser'] != $messageData['senderid']) {
    if ($_SESSION['loggedInUser'] != $messageData['userid']) {
        header('Location: ../feed.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>

<body id="messageScreen">
    <div id="profileWrapper" class="profileWrapper">
        <h1 id="messageTitle"> <?= $title ?></h1>
        <p id="messageText"><?= $message ?></p>
        <div id="bottomMessage">
            <?php if ($_SESSION['loggedInUser'] === $messageData['userid']) {
                echo '<a href="messages.php?user=' . $senderName['name'] . '&userid=' . $messageData['userid'] . '"><h2>Reply</h2></a>';
            } ?>
            <a href="messages.php">
                <h2>Return</h2>
            </a>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>