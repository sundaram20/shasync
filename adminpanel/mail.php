<?php 
//include_once('include/autoloader.php');
//include("PHPMailer/PHPMailerAutoload.php");
include_once('class.phpmailer.php');
//require_once('PHPMailer/class.smtp.php');
//echo $gm_email=  selectColumn('mst_hotels',"email",'WHERE id="123" ');
//echo $gm_email=str_replace(';', ',', $gm_email)
//$recipients =explode(";",$gm_email);
//print_r($recipients);
//$recipients = array(
  // 'person1@domain.com' => 'Person One',
 //  'person2@domain.com' => 'Person Two',
   // ..
//);
//define ('GUSER',"support@roomstatushub.com");
//	define ('GPWD',"room9876#");
echo $mail->Username = "support@roomstatushub.com";
echo $mail->Password = "simlim9876#";

//$mail->Host = "ssl://smtp.gmail.com"; 
//die;
$mail = new PHPMailer(); // create a new object
		

$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "support@roomstatushub.com";
$mail->Password = "Gcs9876#";

//$mail->Username = "webquery@welcomheritagehotels.in";
//$mail->Password = "%8ydRHWR";
					
			
$mail->SetFrom("roomstatushublogs@gmail.com");
$mail->Subject = "Test mail welcomheritagehotels.in";
//$mail->addAttachment("PickupReport_2021-01-10 12 03 09.pdf","tessr.pdf","base64","application/pdf");
$mail->Body = "sir, <br> test mail for aws server ";
$mail->AddAddress("shashafeer@gmail.com");
$mail->AddReplyTo("roomstatushublogs@gmail.comW", 'Status');
foreach($recipients as $name)
{
	//echo '<br>'.$name;
  // $mail->AddCC($name);
}
$mail->AddCC("support@roomstatushub.com");
//$mail->AddCC("sundaram@roomstatushub.com");
 if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
 } else {
    echo "Message has been sent";
 }
/*//Set who the message is to be sent from
$mail->setFrom("fromaddress@domain.com", "From Name");

//Set an alternative reply-to address
$mail->addReplyTo("replyaddress@domain.com", "Reply Name");

//Set to address
$mail->addAddress("address@domain.com", "Some Name");

//Set CC address
$mail->addCC("ccaddress@ccdomain.com", "Some CC Name");

//Set BCC address
$mail->addBCC("bccaddress@ccdomain.com", "Some BCC Name");

//Set the subject line
$mail->Subject = "Test message";

//Set the body
$mail->Body = file_get_contents("/messagestore/some.html");

//Attach a file
$mail->addAttachment("/messagestore/some.pdf","some.pdf","base64","application/pdf");

//generate mime message
*/

?>