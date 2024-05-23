<?php
session_start();
include 'partials/_dbconnect.php';

// Initialize $searchTerm variable
$searchTerm = '';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Make sure to exit after redirection
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Check if the required data is set
    if (isset($_POST['circle_id']) && isset($_POST['month'])) {
        // Get the selected month and other form data
        $moneyCircleId = $_POST['circle_id'];
        $selectedMonth = $_POST['month'];
        $userId = $_SESSION['user_id'];

        // Check if the user has already joined this money circle
        $checkSql = "SELECT * FROM booking WHERE user_id='$userId' AND money_circle_id='$moneyCircleId'";
        $checkResult = mysqli_query($conn, $checkSql);
        if (mysqli_num_rows($checkResult) > 0) {
            // User already joined this money circle
            echo "You have already joined this money circle.";
            exit();
        }

        // Check if the selected month is already taken in this money circle
        $checkMonthSql = "SELECT * FROM booking WHERE money_circle_id='$moneyCircleId' AND month='$selectedMonth'";
        $checkMonthResult = mysqli_query($conn, $checkMonthSql);
        if (mysqli_num_rows($checkMonthResult) > 0) {
            // Selected month is already taken in this money circle
            echo "The selected month is already taken in this money circle.";
            exit();
        }

        // Prepare and execute the SQL query to insert into the booking database
        $insertSql = "INSERT INTO booking (user_id, money_circle_id, month) VALUES ('$userId', '$moneyCircleId', '$selectedMonth')";
        $insertResult = mysqli_query($conn, $insertSql);

        if ($insertResult) {
            // Insertion successful, redirect to success page
            header("Location: success.php");
            exit();
        } else {
            // Insertion failed, handle the error
            echo "Error: " . mysqli_error($conn);
            // You can choose to display an error message or redirect to an error page
            // header("Location: error.php");
            // exit();
        }
    } else {
        // If the required data is missing, redirect to the appropriate page
        header("Location: all_moneycircle.php");
        exit();
    }
}

// Fetch all money circles from the database
$sql = "SELECT * FROM money_circle";
$result = mysqli_query($conn, $sql);

// Check if there are any money circles available
if (mysqli_num_rows($result) > 0) {
    $moneyCircles = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the case where no money circles are available
    $moneyCircles = [];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Money Circle</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/table.css">

    <style>
        .search-form {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search-form button {
            padding: 10px 20px;
            border: none;
            background-color: grey;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }

        .search-form button:hover {
            background-color: black;
        }
    </style>
</head>

<body>
    <?php include 'partials/_navbar.php'; ?>

    <div class="cover"></div>
    
    <h1>ALL &nbsp; Available Money Circles</h1>

    <!-- Search Form -->
    <div class="search-form">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search by amount" value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="all_users" style="height: 500px;">
        <table>
            <tr>
                <th>ID</th>
                <th>Amount of the circle</th>
                <th>Amount you will pay in month</th>
                <th id="join">OPERATION</th>
            </tr>
            <?php
            foreach ($moneyCircles as $circle) {
                $paymentPerMonth = $circle['amount'] / 12;
                echo '
                <tr>
                    <td>' . $circle['money_circle_id'] . '</td>
                    <td>' . $circle['amount'] . '</td>
                    <td>' . $paymentPerMonth . '</td>
                    <td id="join">
                        <form method="POST" action="">
                            <input type="hidden" name="circle_id" value="' . $circle['money_circle_id'] . '">
                            <select name="month">
                                <option value="january">January</option>
                                <option value="February">February</option>
                                <option value="March">March</option>
                                <option value="April">April</option>
                                <option value="May">May</option>
                                <option value="June">June</option>
                                <option value="July">July</option>
                                <option value="August">August</option>
                                <option value="September">September</option>
                                <option value="October">October</option>
                                <option value="November">November</option>
                                <option value="December">December</option>
                                </select>
                                <button type="submit" name="submit">Join</button>
                                </form>
                            </td>
                        </tr>
                        ';
                    }
                    ?>
                </table>
            </div>
        
            <?php include 'partials/_footer.php'; ?>
            <!-- script -->
            <script src="js/navscroll.js"></script>
        </body>
        
        </html>
        