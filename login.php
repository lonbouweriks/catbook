<?php
session_start();
if (isset($_SESSION['loggedInUser'])) {
    header('Location: index.php');
    exit();
}

include 'db.php';

try {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM userdb WHERE email= ?;");
        $stmt->execute([$email]);
        $gebruikersData = $stmt->fetch();

        if (isset($gebruikersData['password'])) {
            if (password_verify($password, $gebruikersData['password'])) {
                $_SESSION["loggedInUser"] = $gebruikersData['userid'];
                header('Location: index.php');
            } else {
                $wrongLogin = "onjuiste gebruikersnaam of wachtwoord.";
            }
        } else {
            $wrongLogin = "onjuiste gebruikersnaam of wachtwoord.";
        }
    }
} catch (PDOException $e) {
    $return = "Wrong password or usernames.: " . $e->getMessage();
    echo $return;
}

try {
    if (isset($_POST['register'])) {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $gender = $_POST['gender'];
        $species = $_POST['species'];
        $breed = $_POST['breed'];
        $stmt = $pdo->prepare("SELECT email FROM userdb WHERE email= ?;");
        $stmt->execute([$email]);
        $gebruikersData = $stmt->fetch();
        $stmt = $pdo->prepare("SELECT name FROM userdb WHERE name= ?;");
        $stmt->execute([$name]);
        $username = $stmt->fetch();


        //upload

        if (isset($_FILES['fileToUpload']) && !isset($gebruikersData['email'])) {
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
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }
        }

        if (isset($gebruikersData['email'])) {
            $emailAlreadyUsed = "Email already used.";
        } else if ($username) {

            $usernameAlreadyUsed = "Username already used.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO
    `userdb` (
        name,
        email,
        password,
        gender,
        species,
        breed,
        profilepicture
    )
values
    (
        :name, 
        :email,
        :password,
        :gender,
        :species,
        :breed,
        :profilepicture
    )");
            $stmt->bindparam(':name', $name);
            $stmt->bindparam(':password', $password);
            $stmt->bindparam(':email', $email);
            $stmt->bindparam(':gender', $gender);
            $stmt->bindparam(':species', $species);
            $stmt->bindparam(':breed', $breed);
            $stmt->bindparam(':profilepicture',  $target_file_new);
            $stmt->execute();
        }
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
    <title>Catbook</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="orderwrapper">
        <div class="wrapper">
            <header>
                <h1 class="logo">catbook</h1>
                <h2 class="subheader">Connect with cats and the world around you on Catbook.</h2>
            </header>
            <div class="register" id="register" <?php if (isset($_POST['register'])) {
                                                    echo  'style="display:unset"';
                                                } ?>>
                <div>
                    <form action="login.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="register" id="registration" value="hidden">
                        <input type="text" name="name" id="name" placeholder="name" required>
                        <input type="email" name="email" id="email" placeholder="email" required>
                        <input type="password" name="password" id="password" placeholder="password" required>

                        <select name="gender" id="gender" required>
                            <option selected disabled value="">Select your gender...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>

                        <select onchange="checkSpecies()" name="species" id="species" required>
                            <option selected disabled value="">Select your species...</option>
                            <option value="cat">Cat</option>
                            <option value="human">Human</option>
                        </select>
                        <input type="text" name="breed" id="breed" placeholder="cat breed">
                        <div class=registrationpicture>
                            <label for="userfile">Profile Picture:</label>
                            <input type="file" id="fileToUpload" name="fileToUpload" accept="image/*" required>
                        </div>
                        <input type="submit" value="Create" name='submit'>
                        <?php if (isset($emailAlreadyUsed)) {
                            echo "<p>$emailAlreadyUsed</p>";
                        }
                        if (isset($usernameAlreadyUsed)) {
                            echo "<p>$usernameAlreadyUsed</p>";
                        }
                        if (isset($return)) {
                            echo "<p>$return</p>";
                        }
                        ?>
                        <p><a href="forgot.php">Forgot Password?</a></p>
                    </form>
                </div>
                <div>
                    <button class="loginbutton" onclick="registerLoginSwitch('login')">Login</button>
                </div>
            </div>
            <div class="login" id="login" <?php if (isset($_POST['register'])) {
                                                echo  'style="display:none"';
                                            } ?>>
                <div>
                    <form action="login.php" method="POST">
                        <input type="hidden" name="login" id="loginForm" value="hidden">
                        <input name="email" type="text" placeholder="email" required>
                        <input name="password" type="password" placeholder="password" required>
                        <input type="submit" value="Log In">
                        <?php if (isset($wrongLogin)) {
                            echo "<p>$wrongLogin</p>";
                        }
                        ?>
                        <p><a href="forgot.php">Forgot Password?</a></p>
                    </form>
                </div>
                <div>
                    <button onclick="registerLoginSwitch('create')">Create New Account</button>
                </div>
            </div>

        </div>
        <footer>
            Catbook 2021
        </footer>
    </div>
    <script src="script.js"></script>
</body>

</html>