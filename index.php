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
			$stmt = $con->prepare("SELECT email FROM user WHERE email =? ");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($email);
			if ($stmt->num_rows > 0) {
				$msg = "Email id already present <h3> <a href='$url/unsubscribe.php'> Click here to Unsubscribe Now. </a></h3>";
			} else {
				$token = bin2hex(random_bytes(15));
				$status = "inactive";
				$stmt = $con->prepare("INSERT INTO user (email, token, status) VALUES (?, ?, ?)");
				$stmt->bind_param("sss", $email, $token, $status);
				$stmt->execute();
				$stmt->close();
				$msg = "We have just sent a verification link to <strong>$email</strong>. Please check your inbox and click on the link to get started. If you can not find this email then check spam folder";
				$htmlContent = "Please confirm your account registration by clicking on the link below: <a href='$url/subscribe_check.php?id=$token'>$url/subscribe_check.php?id=$token</a>";
				$subject = 'Account Verification';
				mail($email, $subject, $htmlContent, $headers);
			}
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
		<h2>Subscribe Us to get random<br>
			XKCD comics every five minutes.</h2>
		<form method="post">
			<div class="inputbox">
				<input type="email" name="email" placeholder="Enter Your Email" required="required">
			</div>
			<div class="inputbox">
				<button type="submit" name="submit">Subscribe Now</button>
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