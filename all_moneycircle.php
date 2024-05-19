<?php

include 'partials/_dbconnect.php';

// Initialize an empty variable for the search term
$searchTerm = '';

// Check if a search term is submitted
if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search']);
    $sql = "SELECT * FROM `money_circle` WHERE `amount` = '$searchTerm'";
} else {
    $sql = "SELECT * FROM `money_circle`";
}

$result = mysqli_query($conn, $sql);

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
    <link rel="stylesheet" href="css/table.css">
    <title>ALL AVAILABLE MONEY CIRCLES</title>

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
            while ($row = mysqli_fetch_assoc($result)) {
                $paymentPerMonth = $row['amount'] / 12;
                echo '
                <tr>
                    <td>' . $row['money_circle_id'] . '</td>
                    <td>' . $row['amount'] . '</td>
                    <td>' . $paymentPerMonth . '</td>
                    <td id="join"><a href="transfer_money.php?id=' . $row['money_circle_id'] . '"><button type="button">Join</button></a></td>
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
