<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

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

if (!isset($_GET['number'])) {
    $number = 0;
} else {
    $number = $_GET['number'];
}
$stmt = $pdo->prepare("SELECT * FROM userdb WHERE species = ? AND userid > ?;");
$stmt->execute([$species, $number]);
$gebruikersData = $stmt->fetch();

if ($gebruikersData) {
    $useridcat = $gebruikersData['userid'];
    $naam = htmlspecialchars($gebruikersData['name']);
    $email = $gebruikersData['email'];
    $gender = htmlspecialchars($gebruikersData['gender']);
    $species = htmlspecialchars($gebruikersData['species']);
    $breed = htmlspecialchars($gebruikersData['breed']);
    $picture = $gebruikersData['profilepicture'];
    $description = htmlspecialchars($gebruikersData['description']);
} else {
    header('Location: empty.php');
    exit();
}

$redirect = 'messages.php?userid=' . $useridcat;

if (isset($_GET['direction'])) {
    if ($_GET['direction'] === "right") {
        $stmt = $pdo->prepare("UPDATE userdb 
        SET
        friends=friends +1
        WHERE userid=:id");
        $stmt->bindparam(':id', $_GET['number']);
        $stmt->execute();
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
    <link rel="stylesheet" href="style.css">
    <script src="https://hammerjs.github.io/dist/hammer.js"></script>
</head>

<body id="swipe">
    <script>
        let useridcat = <?= $useridcat ?>
    </script>

    <h1 class="matchName"><?= $naam ?></h1>
    <div class="matchingProfileTop">
        <h3><?= $gender ?></h3>
        <h3><?= $breed ?></h3>
    </div>

    <div id="profileWrapper" class="profileWrapper">
        <a class="profilePicture" href=<?= $picture ?>>
            <img class="profilePicture" src="<?= $picture ?>" alt="" srcset="" />
        </a>
        <p class="matchingDescription"><?= $description ?></p>

        <div id="mail"> <a class="mailid" href="messages/messages.php?user=<?= $naam ?>&userid=<?= $useridcat ?>">
                <svg version=" 1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 348.165 348.165" style="enable-background:new 0 0 348.165 348.165;" xml:space="preserve">
                    <g>
                        <g>
                            <polygon points="0,60.58 0,71.921 174.083,193.19 348.165,71.921 348.165,60.58 		" />
                            <polygon points="174.083,217.356 71.545,145.228 0,95.39 0,287.585 348.165,287.585 348.165,95.39 
			276.62,145.228 		" />
                        </g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                    <g>
                    </g>
                </svg>
            </a>
        </div>

    </div>
    <svg class="question" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 302.967 302.967" style="enable-background:new 0 0 302.967 302.967;" xml:space="preserve">
        <g>
            <g>
                <g>
                    <path style="fill:#010002;" d="M151.483,302.967C67.956,302.967,0,235.017,0,151.483S67.956,0,151.483,0
				s151.483,67.956,151.483,151.483S235.017,302.967,151.483,302.967z M151.483,24.416c-70.066,0-127.067,57.001-127.067,127.067
				s57.001,127.067,127.067,127.067s127.067-57.001,127.067-127.067S221.555,24.416,151.483,24.416z" />
                </g>
                <g>
                    <g>
                        <path style="fill:#010002;" d="M116.586,118.12c1.795-4.607,4.297-8.588,7.511-11.961c3.225-3.389,7.114-6.016,11.667-7.898
					c4.547-1.904,9.633-2.845,15.262-2.845c7.261,0,13.32,0.995,18.183,2.997c4.857,1.996,8.768,4.482,11.738,7.441
					c2.964,2.97,5.091,6.168,6.369,9.584c1.273,3.432,1.915,6.636,1.915,9.595c0,4.901-0.642,8.947-1.915,12.118
					c-1.278,3.171-2.866,5.88-4.759,8.131c-1.898,2.252-3.987,4.172-6.293,5.755c-2.295,1.588-4.471,3.171-6.516,4.759
					c-2.045,1.583-3.862,3.394-5.445,5.439c-1.588,2.04-2.589,4.601-2.991,7.664v5.831H140.6v-6.908
					c0.305-4.395,1.153-8.072,2.529-11.036c1.382-2.964,2.991-5.499,4.83-7.598c1.844-2.089,3.786-3.911,5.836-5.445
					c2.04-1.539,3.927-3.073,5.673-4.591c1.73-1.545,3.144-3.225,4.221-5.069c1.071-1.833,1.556-4.15,1.452-6.908
					c0-4.705-1.148-8.18-3.454-10.427c-2.295-2.257-5.493-3.378-9.589-3.378c-2.758,0-5.134,0.533-7.131,1.605
					s-3.628,2.513-4.911,4.302c-1.278,1.795-2.225,3.894-2.834,6.288c-0.615,2.415-0.919,4.982-0.919,7.756h-22.55
					C113.85,127.785,114.791,122.732,116.586,118.12z M162.536,183.938v23.616h-24.09v-23.616H162.536z" />
                    </g>
                </g>
            </g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
        <g>
        </g>
    </svg>
    <div class="tooltip">
        <p>Swipe left to dislike the cat or right to like the cat.
            After liking the cat you are able to send messages.</p>
    </div>
    <script src="script.js"></script>
</body>

</html>