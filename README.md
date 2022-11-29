# XKCD COMICS PHP APPLICATION

* Live link - http://ec2-13-115-222-153.ap-northeast-1.compute.amazonaws.com

* Demo video - https://drive.google.com/file/d/1chI9-0u_hynczBGOHWGhKJkAmY_eVHXh/view?usp=share_link

This is a simple Php application that accept visitors emails verify them and then send random comics every 5 minutes.

1. Responsive ui design.
2. When you enter your email for subscribing, first it validate entered email.
3. Then send a verification link to email to verify that its your email.
4. after clicking on the link you get subscribed it.
5. And Now you will receive Random XKCD comics every 5 minutes.
6. With comics you will also get a link to unsubscribe it, when you click on it ,it redirect to unsubscribe page.
7. Then you have to enter your email and its send a verification link to your email.
8. after clicking on the link you get successfully unsubscribe it and after that you will not receive any XKCD comics.

# workflow

1. index.php -  A form page that accept visitor email after validation and check this email is available or not in database and for every email it generate a unique token and stored in database where initialy email status is 'inactive' and send a link to email that veryfy its your email.

2. subscribe_check.php - After clicking on this verification link its update the status of email 'inactive' to 'active'.

3. unsubscribe.php - A form page that accept email after validation and check its in database or not and then send a verification link to email.

4. unsubscribe_check.php - After clicking on the link its delete the user email from database.

5. send_comic.php - For sending a random XKCD comic after every 5 minutes to the user whose status are 'active'.



# Note

1. For Web Hosting, I used amazon aws.

2. For Email sending services , I used php mail() function with SMTP with Gmail Using Postfix.

3. Emails may be go to spam folder.

4. After subscribe to it you will start getting random xkcd comic within 5 minutes.# XKCD-COMICS
