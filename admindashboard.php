<?php include 'partials/_footer.php';
session_start();


$firstName = '';

if (!empty($_SESSION['ID'])) {
    $email = $_SESSION['Email'];

    // Retrieve the user's first name from the database
    $sql = "SELECT firstname FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $firstName = $row['firstname'];
    }
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
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/footer.css">
    
    <title>Gam3eyetna</title>
</head>

<body>

    <div class="cover"></div>

    <div class="container">

        <div class="welcome">
            <h3>Welcome to </h3>
            <h1>Admindashboard</h1>
        </div>

        <div class="allbtns">
            <a href="all_user.php"><button type="button"><i class="fas fa-users"></i>View Contributed Users &nbsp;&nbsp;&nbsp; </button></a>
            <a href="index.php"><button type="button"><i class="fas fa-users"></i>Home &nbsp;&nbsp;&nbsp; </button></a>
            <a href="signout.php"><button type="button"><i class="fas fa-users"></i>Logout &nbsp;&nbsp;&nbsp; </button></a>

            <a href="transferlog.php"><button type="button"><i class="fas fa-user"></i> Transfer Log &nbsp;&nbsp;&nbsp; </button></a>
            <a href="create_circle.php"><button type="button"><i class="fas fa-hand-holding-usd"></i> Create Circle &nbsp;&nbsp;&nbsp; </button></a>
            <!-- <a href="all_moneycircle.php"><button type="button"><i class="fas fa-history"></i> Available Money Circles &nbsp;&nbsp;&nbsp; </button></a> -->
        </div>
        
    </div>

   
    <!-- scripts  -->
    <script src="js/navscroll.js"></script>
</body>

</html>