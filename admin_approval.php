<?php
session_start();
include 'partials/_dbconnect.php';

// Check if the user is an administrator
if (!isset($_SESSION["UserType"]) || $_SESSION["UserType"] !== "admin") {
    echo "You are not authorized to access this page.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contribution_id = $_POST['contribution_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        // Approve the contribution
        $sql_approve = "UPDATE `pending_contributions` SET `status`='approved' WHERE `id`='$contribution_id';";
        $result_approve = mysqli_query($conn, $sql_approve);

        // Move to the money_circle table if approved
        if ($result_approve) {
            $sql_move = "INSERT INTO `money_circle` (`amount`)
                         SELECT `amount` FROM `pending_contributions` WHERE `id`='$contribution_id';";
            mysqli_query($conn, $sql_move);
        }
    } elseif ($action == 'reject') {
        // Reject the contribution
        $sql_reject = "UPDATE `pending_contributions` SET `status`='rejected' WHERE `id`='$contribution_id';";
        mysqli_query($conn, $sql_reject);
    }
}

// Fetch all pending contributions
$sql = "SELECT * FROM `pending_contributions` WHERE `status`='pending';";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css"
        integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="cover"></div>
    <div class="admin-approval">
        <h1>Pending Contributions</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="contribution_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="approve">Approve</button>
                    </form>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="contribution_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="action" value="reject">Reject</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
    <?php include 'partials/_footer.php'; ?>
    <!-- scripts  -->
    <script src="js/navscroll.js"></script>
</body>
</html>
