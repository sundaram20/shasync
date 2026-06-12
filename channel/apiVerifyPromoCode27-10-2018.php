<?php include_once("../config/fron_autoload.php"); 
/////////////////////////////////////////////////////////////

   
$postData = file_get_contents('php://input');

//$postData = file_get_contents('testbooking.xml');

$xmlarray= json_decode($postData);



foreach($xmlarray  as $key=>$value){
 $filter_id[$key] = $value;
}


 $sql 			= "SELECT * FROM `fs_promo_code_details` WHERE  `promo_code` LIKE '%".addslashes(encryptor(decrypt,$filter_id['promocode']))."%' AND CURDATE() between date_valid_from and date_valid_to";
$res 			= mysql_query($sql);
$NumberOfRows	= mysql_num_rows($res);
$Fetch_Record	= mysql_fetch_array($res);

if($NumberOfRows >0){
	
		if($Fetch_Record['promo_code_status'] =='1' || $Fetch_Record['promo_code_status'] =='2' || $Fetch_Record['promo_code_status'] =='3'){  //PomoCode Issues or reissued
	
			
				//Email Code
				
					$updateEvocher = executeSql("UPDATE  `".TBL_PROMO_CODE_DETAILS."`  SET 
								`generate_email`='".$filter_id['verifyemail']."',`generate_date`='".date('Y-m-d')."'							
								where 
								`promo_code` LIKE '%".addslashes(encryptor(decrypt,$filter_id['promocode']))."%'");
				
		
		
				$content = '<style>


.table-bordered {
    border: 1px solid #000;
}
.table {
    margin-bottom: 20px;
    max-width: 80%;
    width:100%;
} 
table {
    background-color: transparent;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000;
    font-size: 0.90em;
    padding: 7px !important;
}</style>';		$passCodeUrl = "https://welcomheritagehotels.in/active_passcode.php?pid=".$filter_id['promocode'];
				$content .= '<table class="table">
						<tr><td colspan="3">Dear ';

						
						$content .= ' Sir/Madam,'; 

								

		$content .=	'</td></tr>
		<td colspan="3">  Greetings !!!<br><br>       

							We are delighted to provide Evoucher Pass Code mentioned below:<br><br>	  </td>';

		$content .=	'<tr >
						<td>
						Pass Code : <b>'.rtrim($Fetch_Record['pass_code']).'</b></td>
						
						
						</tr>
						<tr>
							<td >
								Kindly  open the following URL to enter the Pass Code : <br>
								URL : '.$passCodeUrl.' 
							</td>
						</tr>

						<tr>
							<td>
								Regards,<br>
								Welcomheritagehotels
							<td>
						</tr>
						</table>
						';
						$Emails	=	$filter_id['verifyemail'];
						$subject	=	'Evoucher Code Generated';
						
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset=iso-8859-1';
						
						// Additional headers
						$headers[] = 'To:'.$Emails;
						$headers[] = 'From:evoucher@welcomheritagehotels.in';
						//$headers[] = 'Cc:'.$Emails;
						//$headers[] = 'Bcc: ';
						
						// Mail it
						$Mails=	mail($Emails, $subject, $content, implode("\r\n", $headers));


				
				
					$Meassage	=	"Evoucher Pass Code and URL Generated sucessfully and sent to your Email address.";
	
	
		}elseif($Fetch_Record['promo_code_status'] =='4'){ //PromoCode Is Used
			$Meassage	=	"Evoucher code already Used";			
			}
	
}else{	
$Meassage	=	"Invalide Evoucher Code";
	}
	
	
	
		echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$reference.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>'.$Meassage.'</Success>
				</OTA_HotelResNotifRS>';


					

//}

?>