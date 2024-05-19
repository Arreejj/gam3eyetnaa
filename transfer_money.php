<?php
include 'partials/_dbconnect.php';
if (isset($_GET['id'])) {
    $moneyCircleId = $_GET['id'];
    // Prepare the SQL query to select the specified money circle
    $sql = "SELECT * FROM money_circle WHERE money_circle_id = $moneyCircleId";
    $result = mysqli_query($conn, $sql);

    // Fetch reserved months
    $reservedMonthsSql = "SELECT month FROM money_circle_reservations WHERE money_circle_id = $moneyCircleId";
    $reservedMonthsResult = mysqli_query($conn, $reservedMonthsSql);
    $reservedMonths = [];
    while ($reservedRow = mysqli_fetch_assoc($reservedMonthsResult)) {
        $reservedMonths[] = $reservedRow['month'];
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
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/table.css">   
    <title>TRANSFER MONEY</title>
</head>

<body>
    <?php include 'partials/_navbar.php'; ?>

    <div class="cover"></div>

    <h1>TRANSFER &nbsp; MONEY</h1>
    <div class="all_users" style="height: 500px;">
        <table>
            <tr>
                <th>MONEY CIRCLE ID</th>
                <th>AMOUNT OF WHOLE CIRCLE</th>
                <th>BALANCE YOU WILL PAY</th>
                <th>MONTH</th>
                <th>OPERATION</th>
            </tr>
            <?php 
            while ($row = mysqli_fetch_assoc($result)) {
                $paymentPerMonth = $row['amount'] / 12;
                echo '
                <tr>
                    <td>'.$row['money_circle_id'].'</td>
                    <td>'.$row['amount'].'</td>
                    <td>'.$paymentPerMonth.'</td>
                    <td>
                    <select name="month" id="month_'.$row['money_circle_id'].'">';
                    $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    foreach ($months as $month) {
                        if (in_array($month, $reservedMonths)) {
                            echo '<option value="'.$month.'" disabled>'.$month.' (Reserved)</option>';
                        } else {
                            echo '<option value="'.$month.'">'.$month.'</option>';
                        }
                    }
                    echo '
                    </select>
                </td>
                 <td id="join"; ><a href="transfer_money.php?id= '.$row['money_circle_id'].'"> <button type="button">join</button></a></td> 
                </tr>
                ';
            }
            ?>
        </table>
    </div>

    <?php include 'partials/_footer.php'; ?>
 <!-- script  -->
 <script src="js/navscroll.js"></script>
    <script>
        function setMonth(circleId) {
            var selectedMonth = document.getElementById('month_' + circleId).value;
            var joinLink = document.querySelector('a[href^="transfer_process.php?id=' + circleId + '"]');
            joinLink.href = 'transfer_process.php?id=' + circleId + '&month=' + selectedMonth;
            return true;
        }
    </script>
    <script src="js/navscroll.js"></script>
</body>

</html>