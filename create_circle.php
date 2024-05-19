<?php
session_start();
include 'partials/_dbconnect.php';
// Check if the user is an administrator
if (!isset($_SESSION["UserType"]) || $_SESSION["UserType"] !== "admin") {
    echo "You are not authorized to access this page.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include 'partials/_dbconnect.php';

    $amount = $_POST['amount'];

    // Calculate the amount each participant should contribute
    $individual_amount = $amount / 12;

    if ($individual_amount < 500) {
        // if individual contribution amount is less than 500
        echo "<script> alert('Amount per participant should be 500 or more.'); </script>";
    } else {
        // Inserting contribution data for each participant

        $sql = "INSERT INTO `money_circle` ( `amount`) VALUES ( '$amount');";
        $result = mysqli_query($conn, $sql);


        if ($result) {
            echo "<script> alert('Congrats!! You have successfully contributed to the money circle.'); </script>";
        }
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
    <title>Create Money Circle</title>
</head>

<body>

    <div class="cover"></div>

    <div class="create">
        <h1>CREATE MONEY CIRCLE</h1>
        <div class="createUser">
            <div class="userdata">
                <!-- Contribute Form -->
                <form method="POST">
                    <input id="amount" type="number" placeholder="TOTAL AMOUNT YOU WANT TO CONTRIBUTE" name="amount" required>
                    <button type="submit">CONTRIBUTE</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'partials/_footer.php'; ?>
    <!-- scripts  -->
    <script src="js/navscroll.js"></script>
</body>

</html>
