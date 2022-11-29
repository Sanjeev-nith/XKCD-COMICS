<?php
require __DIR__ . '/config file/db_con.php';
require __DIR__ . '/config file/url_name.php';

$msg = '';

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_GET['id'])) {
    $token = mysqli_real_escape_string($con, test_input($_GET['id']));
    $stmt = $con->prepare("SELECT email FROM user WHERE token =? ");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email);
    if ($stmt->num_rows > 0) {
        $status = 'active';
        $stmt = $con->prepare("UPDATE user SET status = ? WHERE token = ?");
        $stmt->bind_param("ss", $status, $token);
        $stmt->execute();
        $stmt->close();
        $msg = "<h1>Thanks for Subscribing Us.<br/>You will start getting XKCD comics within 5 minutes.</h1> <h3> <a href='$url/unsubscribe.php'> Click here to Unsubscribe Now. </a></h3>";
    } else {
        $msg = "<h1>Invalid link </h1> <h3><a href='$url/index.php'> Resend Confirmation mail. </a></h3>";
    }
}
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XKCD COMIC</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
    <div class="center">
        <?php
        echo $msg;
        ?>
    </div>
</body>

</html>