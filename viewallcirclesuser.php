<?php
session_start();
include 'partials/_dbconnect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in to view your circles.'); window.location.href = 'Account.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch circles the user has joined along with the month
$sql_joined = "SELECT mc.money_circle_id, mc.amount, mc.created_at, bk.month
               FROM money_circle mc
               INNER JOIN booking bk ON mc.money_circle_id = bk.money_circle_id
               WHERE bk.user_id = '$user_id'";
$result_joined = mysqli_query($conn, $sql_joined);

if (!$result_joined) {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
    exit();
}

// Fetch pending money circles
$sql_pending = "SELECT pc.id, pc.amount, pc.status
                FROM pending_contributions pc
                WHERE pc.user_id = '$user_id' AND pc.status = 'pending'";
$result_pending = mysqli_query($conn, $sql_pending);

if (!$result_pending) {
    // Handle query error
    echo "Error: " . mysqli_error($conn);
    exit();
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
    <link rel="stylesheet" href="css/view.css">
    <title>View All Circles</title>
</head>
<body>
    <?php include 'partials/_navbar.php'; ?>

    <div class="container">
        <h1>View All Circles</h1>
        <div class="circles-section">
            <h2>Circles You Have Joined</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Circle ID</th>
                        <th>Amount</th>
                        <th>Month</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result_joined) > 0) {
                        while ($row = mysqli_fetch_assoc($result_joined)) {
                            echo "<tr>";
                            echo "<td>" . $row['money_circle_id'] . "</td>";
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['month'] . "</td>";
                            echo "<td>" . $row['created_at'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No circles joined.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <h2>Pending Circles</h2>
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Contribution ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result_pending) > 0) {
                        while ($row = mysqli_fetch_assoc($result_pending)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['amount'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No pending circles.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'partials/_footer.php'; ?>
    <!-- scripts  -->
    <script src="js/navscroll.js"></script>
</body>
</html>
