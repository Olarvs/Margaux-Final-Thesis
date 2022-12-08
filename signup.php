<?php
include 'includes/session.php';
?>
<?php
require 'includes/PHPMailer.php';
	require 'includes/SMTP.php';
	require 'includes/Exception.php';
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$contact = $_POST['contact'];
		$gender = $_POST['gender'];
		$email = $_POST['email'];
		$address = $_POST['default_delivery_address'];
		$password = $_POST['password'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT COUNT(*) AS numrows FROM clients WHERE email=:email");
		$stmt->execute(['email'=>$email]);
		$row = $stmt->fetch();
		
		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Email already taken';
			header('location: signup.php');
		} else {
			$now = date('Y-m-d');
			$password = password_hash($password, PASSWORD_DEFAULT);

			//generate code
			$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$code = substr(str_shuffle($set), 0, 12);


			$stmt = $conn->prepare("INSERT INTO clients (email, password, firstname, lastname, contact, gender, default_delivery_address, created_on) VALUES (:email, :password, :firstname, :lastname, :contact, :gender, :default_delivery_address, :now)");
			$stmt->execute(['email' => $email, 'password' => $password, 'firstname' => $firstname, 'lastname' => $lastname, 'now' => $now]);
			// $userid = $conn->lastInsertId();

			// Load "phpmailer"
			require 'vendor/autoload.php';

			// 
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPAuth = "true";
			$mail->Port = "587";
			$mail->smtpClose();
			$otp = mt_rand(100000, 999999);
			$mail->SMTPSecure = "tls";
			$mail->Username = 'margauxcsc@gmail.com';
			$mail->Password = "qjptjlezzvfrxiuz";
			$mail->Subject = "Margaux Cacti & Succulents Corner";
			$mail->setFrom("margauxcsc@gmail.com");
			$mail->Body = "Thank you for registering your email account to Margaux Cacti & Succulents Corner!";

			$mail->addAddress($email);
			$mail->Send();
}
?>