<?php
session_start();
if (!isset($_SESSION['loggedInUser'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

$id = $_SESSION["loggedInUser"];
$stmt = $pdo->prepare("SELECT * FROM userdb WHERE userid= ?;");
$stmt->execute([$id]);
$gebruikersData = $stmt->fetch();

$name = htmlspecialchars($gebruikersData['name']);
$email = $gebruikersData['email'];
$gender = htmlspecialchars($gebruikersData['gender']);

$otherGender;
if ($gender === "male") {
    $otherGender = "female";
} else {
    $otherGender = "male";
}

$species = htmlspecialchars($gebruikersData['species']);
$otherSpecies;
if ($species === "cat") {
    $otherSpecies = "human";
} else {
    $otherSpecies = "cat";
}

$breed = htmlspecialchars($gebruikersData['breed']);
$description = htmlspecialchars($gebruikersData['description']);
$picture = $gebruikersData['profilepicture'];

try {
    if (isset($_POST['register'])) {

        $name2 = $_POST['name'] == "" ? $name : $_POST['name'];
        $gender2 = $_POST['gender'];
        $species2 = $_POST['species'];
        $breed2 = $_POST['breed'];
        $description2 = $_POST['description'];
        $file = $_FILES["fileToUpload"];
        $target_file_new = $picture;

        if (isset($_FILES['fileToUpload'])) {
            if ($_FILES["fileToUpload"]["size"] != 0) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $randstring = '';
                for ($i = 0; $i < 30; $i++) {
                    $randstring = $randstring . $characters[rand(0, strlen($characters) - 1)];
                }
                $target_file_new = $target_dir  . $randstring . basename($_FILES["fileToUpload"]["name"]);

                // Check if image file is a actual image or fake image
                if (isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if ($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }

                // Check if file already exists
                if (file_exists($target_file_new)) {
                    echo "An error occured, please try again.";
                    $uploadOk = 0;
                    exit();
                }

                // Check file size
                if ($_FILES["fileToUpload"]["size"] > 900000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file_new)) {
                        echo "The file  has been uploaded.";
                        if (isset($picture)) {
                            unlink($picture);
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }

        $stmt = $pdo->prepare("UPDATE userdb 
            SET       
            gender = :gender,
            species = :species,
            breed = :breed,
            description = :description,
            profilepicture=:profilepicture
            where userid=:id");
        $stmt->bindparam(':id', $id);
        $stmt->bindparam(':gender', $gender2);
        $stmt->bindparam(':species', $species2);
        $stmt->bindparam(':breed', $breed2);
        $stmt->bindparam(':description', $description2);
        $stmt->bindparam('profilepicture', $target_file_new);
        $stmt->execute();
        header('Location: index.php?update=true');
    }
} catch (PDOException $e) {
    $return = "Please fill in every field. <br>error: " . $e->getMessage();
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
</head>

<body id="profile">
    <h1 class="profileName"><?= $name ?></h1>
    <div class="profileFormWrapper">
        <form class="profileForm" action="<?php $_PHP_SELF ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="register" id="register" value="true">

            <label for="gender">Gender</label>
            <select name="gender" id="gender" required>
                <option value="<?= $gender ?>"><?= $gender ?></option>
                <option value="<?= $otherGender ?>"><?= $otherGender ?></option>
            </select>
            <label for="species">Species</label>
            <select onchange="checkSpecies()" name="species" id="species" required>
                <option selected value="<?= $species ?>"><?= $species ?></option>
                <option value="<?= $otherSpecies ?>"><?= $otherSpecies ?></option>
            </select>
            <div id="catBreedProfile">
                <label for="catbreed">Breed</label>
                <input type="text" name="breed" id="catbreed" value="<?= $breed ?>">
            </div>
            <label for="description">Description</label>
            <input type="text" name="description" id="description" value="<?= $description ?>">
            <img class="profileFormPicture" src="<?= $picture ?>" alt="picture">
            <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*">
            <input type="submit" value="Change">
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>