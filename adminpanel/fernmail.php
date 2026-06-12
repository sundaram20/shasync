<?php 

if(isset($_POST['submit'])){
	// To send HTML mail, the Content-type header must be set

	//debugData($_POST);

	$headers=array();

	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';

	// Additional headers
	$headers[] = 'To: '.$to;

	$headers[] = 'From: '.$_POST['fromName'].' <'.$_POST['from'].'> ';
	$headers[] = 'Reply-To: '.$_POST['fromName'].' <'.$_POST['from'].'> ';

	$sent = mail($_POST['to'], 'Fern Mail TEST', $_POST['msg'], implode("\r\n", $headers));
	
	if($sent){
		echo "<script>alert('Mail Sent');</script>";
	}
	else{
		echo "<script>alert('Mail Failed to send');</script>";	
	}

	
}

?>



<form action="" method="POST">
	<label>Enter From Name: </label>
	<input required="required" type="text" name="fromName" value="Mohit Sharad" /><br><br>
	<label>Enter From Email address: </label>
	<input required="required" type="email" name="from" value="support1@roomstatushub.com" /><br><br>
	<label>Enter to Email address: </label>
	<input required="required" type="email" name="to" /><br><br>
	<textarea name="msg" required="required" cols="50" placeholder="Type Your Message..."></textarea><br><br>
	<input type="submit" name="submit">
</form>