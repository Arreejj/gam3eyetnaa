<?php
session_start();
include 'partials/_dbconnect.php';

$errors = []; // Initialize an array to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = htmlspecialchars($_POST["Email"]);
    $Password = htmlspecialchars($_POST["Password"]);

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT * FROM usersignup WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Password'];

        // Verify if the entered password matches the hashed password
        if (password_verify($Password, $hashedPassword)) {
            $_SESSION["ID"] = $row["ID"];
            $_SESSION["FName"] = $row["FirstName"];
            $_SESSION["LName"] = $row["LastName"];
            $_SESSION["Email"] = $row["Email"];
            $_SESSION["UserType"] = $row["UserType"];

            if ($_SESSION["UserType"] == "admin") {
                header("Location: admindashboard.php");
                exit();
            } else {
                header("Location: contact.php");
                exit();
            }
        } else {
            $errors[] = "Invalid password.";
        }
    } else {
        $errors[] = "Email does not exist.";
    }

    $stmt->close();
}

$currentPage = basename($_SERVER["SCRIPT_NAME"]);

if (($currentPage === "admin_addproduct.php" || $currentPage === "admindashboard.php") && (!isset($_SESSION["UserType"]) || $_SESSION["UserType"] !== "admin")) {
    $errors[] = "You are not authorized to access this page.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Login USER</title>
</head>
<body>
<?php include 'partials/_navbar.php'; ?>

<div class="cover"></div>

<div class="create">
    <h1>Login &nbsp; </h1>
    <div class="createUser">
        <div class="userdata">
            <!-- Create user Form -->
            <form action="" method="post">
                <h2>Login</h2>
                <div>
                    <input type="email" name="Email" placeholder="Email" required>
                    <input type="password" name="Password" placeholder="Password" required>
                </div>
                <input type="submit" name="Submit" value="Submit">
            </form>
        </div>
    </div>
</div>

<?php
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

<?php include 'partials/_footer.php'; ?>
<!-- scripts  -->
<script src="js/navscroll.js"></script>
</body>
</html>