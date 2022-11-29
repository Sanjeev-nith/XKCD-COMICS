<?php
require __DIR__ . '/config file/db_con.php';
require __DIR__ . '/config file/url_name.php';
require __DIR__ . '/config file/cronjob_pass.php';

if (isset($_POST['pass'])) {
	$pass = $_POST['pass'];
	if ($pass === $cronjob_http_pass) {
		$status = 'active';
		$arrs = [];
		$stmt = $con->prepare("SELECT email,token FROM user WHERE status = ?");
		$stmt->bind_param("s", $status);
		$stmt->execute();
		$arrs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
		foreach ($arrs as $arr) {
			$email = $arr['email'];
			$headers = get_headers('https://c.xkcd.com/random/comic', 1);
			$comic_url_location = $headers['Location'][1];
			$comic_url = $comic_url_location . '/info.0.json';
			$json = file_get_contents($comic_url);
			$json = json_decode($json);
			$file = file_get_contents($json->img);
			$encoded_file = chunk_split(base64_encode($file));
			$attached_file[] = array(
				'name'     => $json->safe_title . '.jpg',
				'data'     => $encoded_file,
				'type'     => 'application/pdf',
				'encoding' => 'base64',
			);
			$subject = 'XKCD COMIC UPDATE ';
			$htmlContent = "<br>
		Here is a new comic <b>$json->safe_title<b> for you.<br>
		<img src=$json->img alt='Image broken'>
		<br>
		<a href= $comic_url_location>New Comic</a><br>
		Click on the link above to Read.<br>
		<br>
		<a href='$url/unsubscribe.php'>Click here to Unsubscribe</a>
		<br>";

			$eol = "\r\n";
			$headers = 'From: ' . 'XKCD COMIC' . '<' . 'xyz@abc.com' . '>' . $eol;
			$headers .= 'X-Mailer: PHP/' . phpversion() . $eol;
			$headers .= 'MIME-Version: 1.0' . $eol;

			if (empty($attached_file)) {
				$headers .= 'Content-type: text/html; charset=UTF-8' . $eol;
			} else {
				$boundary  = md5(time());
				$headers .= 'Content-type: multipart/mixed;boundary="' . $boundary . '"' . $eol;
			}
			$body = '--' . $boundary . $eol;
			$body .= 'Content-type: text/html; charset="utf-8"' . $eol;
			$body .= 'Content-Transfer-Encoding: 8bit' . $eol;
			$body .= $htmlContent . $eol;
			$body .= '--' . $boundary . $eol;
			$body .= 'Content-Type: ' . 'application/pdf' . '; name="' . $json->safe_title . '.jpg' . '";' . $eol;
			$body .= 'Content-Transfer-Encoding: ' . 'base64' . $eol;
			$body .= 'Content-Disposition: attachment;' . $eol;
			$body .= $encoded_file . $eol;

			mail($email, $subject, $body, $headers);
		}
		mysqli_close($con);
	}
}