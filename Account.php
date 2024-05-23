<?php
session_start();
include 'partials/_dbconnect.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];

    $sql = "SELECT * FROM usersignup WHERE Email='$Email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashedPassword = $row['Password'];

        if (password_verify($Password, $hashedPassword)) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["FName"] = $row["FirstName"];
            $_SESSION["LName"] = $row["LastName"];
            $_SESSION["Email"] = $row["Email"];
            $_SESSION["UserType"] = $row["UserType"];

            if ($_SESSION["UserType"] == "admin") {
                header("Location: admindashboard.php");
                exit();
            } else {
                header("Location: createcircleuser.php");
                exit();
            }
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        $errors[] = "Email does not exist.";
    }
}

if (!empty($errors)) {
    echo '<div class="error-container">';
    echo '<ul class="error-list">';
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo '</ul>';
    echo '</div>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Login USER</title>
</head>
<body>
<?php include 'partials/_navbar.php'; ?>

<div class="cover"></div>

<div class="create">
    <h1>Login</h1>
    <div class="createUser">
        <div class="userdata">
            <form action="" method="post">
                <h2>Login</h2>
                <div>
                    <input type="text" name="Email" placeholder="Email" required="">
                    <input type="password" name="Password" placeholder="Password" required="">
                </div>
                <input type="submit" name="Submit" value="Submit">
            </form>
        </div>
    </div>
</div>
<?php include 'partials/_footer.php'; ?>
<!-- scripts  -->
<script src="js/navscroll.js"></script>
</body>
</html>
