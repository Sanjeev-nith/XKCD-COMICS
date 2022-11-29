<?php
require __DIR__ . '/config file/db_con.php';
require __DIR__ . '/config file/mail_data.php';
require __DIR__ . '/config file/url_name.php';

$msg = '';

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (isset($_POST['submit'])) {
    if (isset($_POST['email'])) {
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $msg = "Please enter a valid email";
        } else {
            $arr = [];
            $stmt = $con->prepare("SELECT * FROM user WHERE email =? ");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $arr = $stmt->get_result()->fetch_assoc();
            if ($arr) {
                $token = $arr['token'];
                $msg = "We've just sent a verification link to $email. Please check your inbox and click on the link to get Unsubscribed. If you can't find this email then check spam folder";
                $htmlContent = "Please confirm to get Unsubscribed by clicking on the link below: <a href='$url/unsubscribe_check.php?id=$token'>$url/unsubscribe_check.php?id=$token</a>";
                $subject = 'Account Verification';
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: ' . $fromName . '<' . $from . '>' . "\r\n";
                mail($email, $subject, $htmlContent, $headers);
            } else {
                $msg = "You have not subscribed Us <a href='$url/index.php'> Click here to Subscribe. </a>";
            }
            $stmt->close();
        }
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
        <h1>XKCD comics</h1>
        <form method="post">
            <div class="inputbox">
                <input type="email" name="email" placeholder="Enter Your Email" required="required">
            </div>
            <div class="inputbox">
                <button type="submit" name="submit">UnSubscribe Now</button>
            </div>
            <div class="inputbox">
                <h3>
                    <?php
                    echo $msg;
                    ?>
                </h3>
            </div>
        </form>
    </div>
</body>
</html>