<?php 
include_once("../config/auto_loader.php");
//sendRateMail($from, $to, $subject, $body, $cc,$attach="",$fromName="",$replyToName="")
//echo 'here';
$sendMail->sendRateMail('hiteshaloney75@gmail.com','support@roomstatushub.com','testing for fern 200','thanks','hiteshaloney94@gmail.com','','Varun');
$sendMail->ErrorInfo;
unset($sendMail);
echo 'working';
?>