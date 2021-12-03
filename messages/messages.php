<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: ../login.php');
    exit();
}

include '../db.php';

$id = $_SESSION["loggedInUser"];
$stmt = $pdo->prepare("SELECT * FROM messagedb WHERE userid= ?;");
$stmt->execute([$id]);
$messageData = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM messagedb WHERE senderid= ?;");
$stmt->execute([$id]);
$SentMessagesData = $stmt->fetchAll();



try {
    if (isset($_POST['name']) && isset($_POST['name']) && isset($_POST['title'])) {

        $user = $_POST['name'];
        $text = htmlspecialchars($_POST['text']);
        if ($_POST['title'] === "") {
            $title = "unnamed";
        } else {
            $title = htmlspecialchars($_POST['title']);
        }
        $stmt = $pdo->prepare("SELECT userid FROM userdb WHERE name= ?;");
        $stmt->execute([$user]);
        $sendMessage = $stmt->fetch();

        if (!isset($sendMessage['userid']) || $id === $sendMessage['userid']) {
            echo "invalid user";
        } else {

            $stmt = $pdo->prepare("INSERT INTO
        `messagedb` (
            userid,
            senderid,
            title,
            message
        )
    values
        (
            :userid, 
            :senderid,
            :title,
            :message
        )");
            $stmt->bindparam(':userid', $sendMessage['userid']);
            $stmt->bindparam(':senderid', $id);
            $stmt->bindparam(':title', $title);
            $stmt->bindparam(':message', $text);
            $stmt->execute();
        }
    }
} catch (PDOException $e) {
    $return = "Please fill in every box. " . $e->getMessage();
    echo $return;
}

if (isset($_GET['user'])) {
    $name = '<input type="text" name="name" id="name" value=' . $_GET['user'] . ' readonly required>';
} else {
    $name = '<input type="text" name="name" id="name" placeholder="name" required>';
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

<body id=messageScreen>

    <div class="messagesWrapper">
        <div>
            <p id="newMessageText">New Message</p>

        </div>
        <div>
            <p id="sentMessageText">Sent Messages</p>

        </div>
        <div>
            <p id="receivedMessageText">Received Messages</p>

        </div>
        <div>
            <p id="returnFeed"><a href="../index.php">Return</a></p>

        </div>
    </div>

    <div id="newMessage">
        <form id="newMessageForm" action="messages.php" method="POST">
            <div><label for="name">Name:</label>
                <?= $name ?>
            </div>
            <div><label for="title">Title:</label>
                <input type="text" name="title" id="title" placeholder="title">
            </div>
            <div>
                <label for="text">Message:</label>
                <textarea rows="40" cols="100" name="text" id="text" placeholder="text" required>
</textarea>
            </div>


            <input type="submit" value="Create" name='submit'>
        </form>
    </div>
    <div id="receivedMessage">
        <h1>Received Messages</h1>

        <?php
        if ($messageData) {
            foreach ($messageData as $message) {
                $userid = $message['senderid'];
                $stmt = $pdo->prepare("SELECT name FROM userdb WHERE userid= ?;");
                $stmt->execute([$userid]);
                $userData = $stmt->fetch();
                echo '<a href="message.php?messageid=' . $message['messageid'] . '"><div class="messageList">';
                echo '<h2>' . $userData['name'] . '</h2>';
                echo '<h2>.' . $message['title'] . '</h2>';


                echo '</div></a>';
            }
        } else {
            echo "No messages received.";
        }


        ?>
    </div>
    <div id="sentMessage">
        <h1>Sent Messages</h1>
        <?php
        if ($SentMessagesData) {
            foreach ($SentMessagesData as $sentMessage) {
                $userid = $sentMessage['userid'];
                $stmt = $pdo->prepare("SELECT name FROM userdb WHERE userid= ?;");
                $stmt->execute([$userid]);
                $sentMessageUserData = $stmt->fetch();
                echo '<a href="message.php?messageid=' . $sentMessage['messageid'] . '"><div class="messageList">';
                echo '<h2>' . $sentMessageUserData['name'] . '</h2>';
                echo '<h2>' . $sentMessage['title'] . '</h2>';
                echo '</div></a>';
            }
        } else {
            echo "No messages sent.";
        }

        ?>
    </div>
    <script src="../script.js"></script>
</body>

</html>