<?php
// include_once "partials/dbh.inc.php";
include 'partials/_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Fname = trim($_POST["FName"]);
    $Lname = trim($_POST["LName"]);
    $Email = trim($_POST["Email"]);
    $Password = $_POST["Password"];
    $ConfirmPassword = $_POST["passConfirm"];

    // Validate input fields
    $errors = [];

    // Check for empty fields
    if (empty($Fname)) {
        $errors[] = "First name is required.";
    }

    if (empty($Lname)) {
        $errors[] = "Last name is required.";
    }

    if (empty($Email)) {
        $errors[] = "Email is required.";
    }

    if (empty($Password)) {
        $errors[] = "Password is required.";
    }

    if (empty($ConfirmPassword)) {
        $errors[] = "Confirm password is required.";
    }

    // Check for valid email format
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if password and confirm password match
    if ($Password !== $ConfirmPassword) {
        $errors[] = "Password and confirm password do not match.";
    }

    // Check minimum password length
    if (strlen($Password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if email is already registered
    $sql = "SELECT COUNT(*) FROM usersignup WHERE Email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $Email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $emailCount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($emailCount > 0) {
        $errors[] = "Email is already registered.";
    }

    if (!empty($errors)) {
        
    } else {
        // Insert data into the database with default user type as "user"
        $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
        $userType = "user"; // Set default user type
        $sql = "INSERT INTO usersignup (FirstName, LastName, Email, Password, UserType) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $Fname, $Lname, $Email, $hashedPassword, $userType);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Redirect the user based on their user type
        if ($userType == "admin") {
            header("Location: admindashboard.php");
        } else {
            header("Location: Account.php");
        }
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

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <title>ADD USER</title>
</head>

<body>
    <?php include 'partials/_navbar.php'; ?>


    <div class="cover"></div>

    <div class="create">
        <h1>CREATE &nbsp; ACCOUNT</h1>
        <div class="createUser">
          
            <div class="userdata">
                <!-- Create user Form -->
                <form action="" method="post" onsubmit="return validate(this)">
                <h2>Create Account</h2>
                
                <div>
                    <input type="text" name="FName" placeholder="First Name" required="">
                    <input type="text" name="LName" placeholder="Last Name" required="">
                </div>
                <div class="err"><span id="FnameERR"></span> <span id="LnameERR"></span></div>
                <input type="email" name="Email" required="" placeholder="Email Address">
                <span id="eErr" class="err"></span>



                <input type="password" name="Password" placeholder="Password" required="">
                <span id="pErr" class="err"></span>
                <input type="password" name="passConfirm" placeholder="Confirm Password" required="">
                <span id="pcErr" class="err"></span>
                <br>
                <select name="months" required="">
                    <option value=""> Months</option>
                    <option value="january">January</option>
                    <option value="february">February</option>
                    <option value="march">March</option>
                    <option value="april">April</option>
                    <option value="may">May</option>
                    <option value="june">June</option>
                    <option value="july">July</option>
                    <option value="august">August</option>
                    <option value="september">September</option>
                    <option value="october">October</option>
                    <option value="november">November</option>
                    <option value="december">December</option>

                </select>
                <select name="Days" required="">
                    <option value="">Day</option>
                    <option value="1"> 1</option>
                    <option value="2"> 2</option>
                    <option value="3"> 3</option>
                    <option value="4"> 4</option>
                    <option value="5"> 5</option>
                    <option value="6"> 6</option>
                    <option value="7"> 7</option>
                    <option value="8"> 8</option>
                    <option value="9"> 9</option>
                    <option value="10"> 10</option>
                    <option value="11"> 11</option>
                    <option value="12"> 12</option>
                    <option value="13"> 13</option>
                    <option value="14"> 14</option>
                    <option value="15"> 15</option>
                    <option value="16"> 16</option>
                    <option value="17"> 17</option>
                    <option value="18"> 18</option>
                    <option value="19"> 19</option>
                    <option value="20"> 20</option>
                    <option value="21"> 21</option>
                    <option value="22"> 22</option>
                    <option value="23"> 23</option>
                    <option value="24"> 24</option>
                    <option value="25"> 25</option>
                    <option value="26"> 26</option>
                    <option value="27"> 27</option>
                    <option value="28"> 28</option>
                    <option value="29"> 29</option>
                    <option value="30"> 30</option>
                    <option value="31"> 31</option>
                </select>
                <input type="submit" name="Submit" value="Create Account">
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
    
    <script src="js/navscroll.js"></script>
</body>

</html>