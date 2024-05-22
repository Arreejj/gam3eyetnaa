<?php
session_start();
include 'partials/_dbconnect.php';

$errors = []; // Initialize an array to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ChangePassword"])) {
    $Email = htmlspecialchars($_POST["Email"]);
    $OldPassword = htmlspecialchars($_POST["OldPassword"]);
    $NewPassword = htmlspecialchars($_POST["NewPassword"]);
    $ConfirmPassword = htmlspecialchars($_POST["ConfirmPassword"]);

    // Validate the form inputs
    if ($NewPassword !== $ConfirmPassword) {
        $errors[] = "New passwords do not match.";
    }

    // Check if the email and old password match
    $sql = "SELECT * FROM usersignup WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Password'];

        if (password_verify($OldPassword, $hashedPassword)) {
            // Valid old password, update the password
            $hashedNewPassword = password_hash($NewPassword, PASSWORD_DEFAULT);
            $updateSql = "UPDATE usersignup SET Password = ? WHERE Email = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ss", $hashedNewPassword, $Email);
            $updateStmt->execute();

            // Redirect or show success message
            $errors[] = "Password updated successfully.";
        } else {
            $errors[] = "Invalid old password.";
        }
    } else {
        $errors[] = "Email not found.";
    }

    $stmt->close();
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
    <title>Change Password</title>
</head>
<body>
<?php include 'partials/_navbar.php'; ?>

<div class="cover"></div>

<div class="create">
    <h1>Change Password</h1>
    <div class="createUser">
        <div class="userdata">
            <!-- Change Password Form -->
            <form action="" method="post">
                <h2>Change Password</h2>
                <div>
                    <input type="email" name="Email" placeholder="Email" required>
                    <input type="password" name="OldPassword" placeholder="Old Password" required>
                    <input type="password" name="NewPassword" placeholder="New Password" required>
                    <input type="password" name="ConfirmPassword" placeholder="Confirm New Password" required>
                </div>
                <input type="submit" name="ChangePassword" value="Change Password">
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