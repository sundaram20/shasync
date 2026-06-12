<?php 
//include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'view');
/*$path = getcwd().'/public_html/sync/adminpanel';
include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/cronRoomstatus.library.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");*/


  		$corpse 	= "test";
        $mail 		= new PHPMailer;
        $mail->isMail();
        $mail->IsHTML(true);
        $mail->From='shashafeer@gmail.com';
        $mail->FromName='shafeer';
        $mail->AddAddress('shashafeer@gmail.com');
        $date 		= date("Ymd", time());
				
       $yesterday 	= date("Ymd", strtotime("-1 day"));
       // if ($this->type == cur)
              //  $pj = "/adminpanel/pdf-template/bookingperiod_".$date.".pdf";
        //else
                $pj = $_SERVER['DOCUMENT_ROOT']."/sync/adminpanel/cron-pdf/SalesReport6_2019-01-02.pdf";
        echo $pj;
        echo "<br>".is_readable($pj) ? 'The file is readable' : 'The file is NOT readable';
        $mail->Subject = (is_readable($pj)) ? 'The file is readable' : 'The file is NOT readable'; // DEBUG

        $mail->AddAttachment($pj);
        $mail->AddReplyTo('shashafeer@gmail.com');
        //$mail->Subject='SubjectOfTheMail';
        $mail->Body=$corpse;
        if (!$mail->Send())
                echo "Error Sending: ".$mail->ErrorInfo;
        unset($mail);
		
		
?>