<?php 

function DiMail($Subject, $Body_Text, $Body_HTML, $From, $FromName, $To, $Cc = "", $Bcc = "", $MailType = 'H', $Attachments = "", $ReplyTo = "", $ReturnPath = "", $CustomHeader = "") {
	Global $HOST_NAME, $MAIL_SERVER_TYPE, $USER_IP;    // These variables comes from config/data.config.php
        $HostName = $HOST_NAME;
	$MailerServerType = $MAIL_SERVER_TYPE; 
	$UserIP = $USER_IP;

	$mail = new phpmailer();

	//Set Body

	if ($MailType == 'T') {
		if ($Body_Text) {
			$mail->Body = $Body_Text;
			$mail->IsHTML(FALSE);
		}
		else {
			return false;
		}
	}
	elseif ($MailType == 'H') {
		if ($Body_HTML)	{
			$mail->Body = $Body_HTML;
			$mail->IsHTML(TRUE);
		}
		else {
			return false;
		}
	}
	elseif ($MailType == 'B') {
		if ($Body_HTML)	{
			$mail->Body = $Body_HTML;
			$mail->AltBody = $Body_Text;	
			$mail->IsHTML(TRUE);
		}
		else {
			return false;
		}
	}
	
	//Set Subject
	$mail->Subject = $Subject;
	$mail->CharSet = "UTF-8";

	$mail->AddCustomHeader("X-Mailer: The Website $HostName Mail System");
	$mail->AddCustomHeader("X-From-IP: $UserIP");

	if ($CustomHeader) {
		if (is_array($CustomHeader)) {
			foreach($CustomHeader as $ExtraHeader) {
				$mail->AddCustomHeader($ExtraHeader);
			}
		}		
	}

	//Set Addressees

	$mail->From = $From;
	$mail->FromName = $FromName;

	if ($ReturnPath) {
		$mail->Sender = $ReturnPath;
	}
	else {
		$mail->Sender = $From;
	}

	if ($ReplyTo) {
		$mail->AddReplyTo($ReplyTo, "");
	}

	if ($To) {
		if (is_array($To)) {
			foreach($To as $to_email=>$to_name) {
				$mail->AddAddress($to_email, $to_name);
			}
		}		
	}
	
	if ($Bcc) {
		if (is_array($Bcc)) {
			foreach($Bcc as $bcc_email=>$bcc_name) {
				$mail->AddBCC($bcc_email, $bcc_name);
			}
		}		
	}

	if ($Cc) {
		if (is_array($Cc)) {
			foreach($Cc as $cc_email=>$cc_name) {
				$mail->AddCC($cc_email, $cc_name);
			}
		}		
	}

	//Extra Setting
	$mail->WordWrap = 76;
	$mail->Priority = 3; // Normal
	
	if ($MailerServerType == "sendmail")
		$mail->IsSendMail();
	if ($MailerServerType == "mail")
		$mail->IsMail();
	if ($MailerServerType == "qmail")
		$mail->IsQmail();
	if ($MailerServerType == "smtp")
		$mail->IsSMTP();

	//Add Attachments

	if ($Attachments) {
		if (is_array($Attachments)) {
			foreach($Attachments as $AFile)	{
				if (file_exists($AFile)) {
					if (is_file($AFile)) {
						$F = explode("/", $AFile);
						$f_name = $F[count($F)-1];
						$mail->AddAttachment($AFile,$f_name,"base64","application/octet-stream");
					}				
				}
			}
		}
	}
	
	$isSent = $mail->Send(); 

	$mail->ClearAllRecipients();
	$mail->ClearReplyTos();
	$mail->ClearCustomHeaders();
	$mail->ClearAttachments();
	
	return $isSent;
}

?>
