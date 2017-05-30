<?php
// require_once('emailconfig.php');
// require('phpmailer/PHPMailer/PHPMailerAutoload.php');
// //include_once "../create_new_account.php";
// $mail = new PHPMailer;
// $mail->SMTPDebug = 0;                               // Enable verbose debug output

// //$mail->isSMTP();                                      // Set mailer to use SMTP
// $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
// $mail->SMTPAuth = true;                               // Enable SMTP authentication


// $mail->Username = EMAIL_USER;                 // SMTP username
// $mail->Password = EMAIL_PASS;                 // SMTP password
// $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
// $mail->Port = 587;                                    // TCP port to connect to
// $options = array(
//     'ssl' => array(
//         'verify_peer' => false,
//         'verify_peer_name' => false,
//         'allow_self_signed' => true
//     )
// );
// $mail->smtpConnect($options);
// $mail->From = $_POST['email'];//your email sending account
// $mail->FromName = 'RoadTrip App';//your email sending account name
// $mail->addAddress($_POST['email']/*your email address, or the email the sender if you are sending confirmation*/, $_POST['username']/*email address user name*/);     // Add a recipient
// //$mail->addAddress('ellen@example.com');               // Name is optional
// $mail->addReplyTo('roadtrip@noreply.com'/*email address of the person sending the message, so you can reply*/);
// //$mail->addCC('cc@example.com');
// //$mail->addBCC('bcc@example.com');

// //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
// //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
// $mail->isHTML(true);                                  // Set email format to HTML

// $mail->Subject = 'Email Verification';
// $username = urlencode($username);
// $message =
//     "
//                 Please Confirm your email 
//                 Click the link below to verify your account
//                  http://cungcancode.com/projects/Roadtrip/signin.php?username=$username&code=$confirmCode
//                 ";
// $mail->Body    = 'Thank you for sign up for our Roadtrip app. ' .$message;
// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

// if(!$mail->send()) {
//     echo 'Message could not be sent.';
//     echo 'Mailer Error: ' . $mail->ErrorInfo;
// } else {
//     echo 'Message has been sent';
// }

// Create the email and send the message
$to = "$email"; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Road Trip verification email";
$email_body = "Please Confirm your email 
              Click the link below to verify your account 
              http://cungcancode.com/projects/Roadtrip/signin.php?username=$username&code=$confirmCode";
$headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: noreply@domain.com";
mail($to,$email_subject,$email_body,$headers);
?>
