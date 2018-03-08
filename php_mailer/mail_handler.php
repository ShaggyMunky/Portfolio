<?php
require_once('email_config.php');
require('phpmailer/PHPMailer/PHPMailerAutoload.php');

//Validate POST inputs
$message = [];
$output = [
    'success' => null,
    'messages' => []
];
echo("<script>console.log('PHP: '+$message);</script>");
// sanitize name field
$message['Name'] = filter_var($_POST['Name'], FILTER_SANITIZE_STRING);
if(empty($message['Name'])){
    $output['success'] = false;
    $output['messages'][]= 'missing name key';
}
// validate email field
$message['Email'] = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
if(empty($message['Email'])){
    $output['success'] = false;
    $output['messages'][]= 'invalid email key';
}
// sanitize message
$message['Message'] = filter_var($_POST['Message'], FILTER_SANITIZE_STRING);
if(empty($message['Message'])){
    $output['success'] = false;
    $output['messages'][]= 'missing message key';
}
if ($output['success'] !== null){
    http_response_code(400);
    echo json_encode($output);
    exit();
}

$mail = new PHPMailer;
//$mail->SMTPDebug = 3;           // Enable verbose debug output. Change to 0 to disable debugging output.

$mail->isSMTP();                // Set mailer to use SMTP.
$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers.
$mail->SMTPAuth = true;         // Enable SMTP authentication


$mail->Username = EMAIL_USER;   // SMTP username
$mail->Password = EMAIL_PASS;   // SMTP password
$mail->SMTPSecure = 'tls';      // Enable TLS encryption, `ssl` also accepted, but TLS is a newer more-secure encryption
$mail->Port = 587;              // TCP port to connect to
$options = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->smtpConnect($options);
$mail->From = $message['Email'];  // sender's email address (shows in "From" field)
$mail->FromName = $message['Name'];   // sender's name (shows in "From" field)
$mail->addAddress(EMAIL_USER);  // Add a recipient
//$mail->addAddress('ellen@example.com');                        // Name is optional
$mail->addReplyTo($message['Email'], $message['Name']);                          // Add a reply-to address
//$mail->addCC('cc@example.com');
//$mail->addBCC('bcc@example.com');

//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$message['Subject'] = substr($message['Subject'], 0, 78);
$mail->Subject = $message['Subject'];

$message['Message'] = nl2br($message['Message']);
$mail->Body    = $message['Message'];
$mail->AltBody = htmlentities($message['Message']);

//Attempt email send, output result to client
if(!$mail->send()) {
    $output['success'] = false;
    $output['messages'][] = $mail -> ErrorInfo;
} else {
    $output['success'] = true;
    $output['messages'][] = 'message sent successfully';
}
echo json_encode($output)
?>
