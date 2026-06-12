<?php

	include_once("../../config/auto_loader.php");
	

	if($_SESSION['followup_hotel_id']!=""){

		foreach($_SESSION['followup_hotel_id'] as $dataCode =>$key){

			$assingToEmail = $_SESSION['assign_followup_user_id'][$dataCode];
			$follow_remarks= $_SESSION['followup_description'][$dataCode];

		}

	}	

	

	if($_REQUEST['forwardEnquiryUser']=='forwardUser'){

		$assingToEmail	=$_REQUEST['assign_user_id'];
		$follow_remarks =$_REQUEST['followup_description'];

	}

	$hotelName=selectColumn(TBL_HOTELS,'CONCAT(name,", ",city)','WHERE id="'.$_POST['id_hotel_md'].'" ');



	$ccData=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$_SESSION['userId'].'" ');	

	

	$to = selectColumn(TBL_USERS,'email','WHERE id="'.$assingToEmail.'" ');

	$toName=selectColumn(TBL_USERS,'name','WHERE id="'.$assingToEmail.'" ');





	$ccAr = explode("|",$ccData);

	$ccEmail = $ccAr[0];

	$ccName =$ccAr[1]; 



	$person = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$_POST['id_contacts'].'" ');

	$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_POST['id_company'].'" ' );

	$emailPer = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$_POST['id_contacts'].'" ');

	$companyNameUser=selectColumn(TBL_USERS,'company','WHERE id="'.$_SESSION['userId'].'" ');

	$id_designation = selectColumn(TBL_USERS,'designation','WHERE id="'.$_SESSION['userId'].'" ');

	$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');

	$address1 = selectColumn(TBL_USERS,'address','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');
	
	$address2 = selectColumn(TBL_USERS,'address2','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');

	$city = selectColumn(TBL_USERS,'city','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');

	$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');

	$mobileCc = selectColumn(TBL_USERS,'mobile','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');



	$infoTable="<table style='margin-bottom:20px;' border='1' cellspacing='0'>
					<tr ><td><b>Hotel Name</b></td><td>".$hotelName."</td></tr>
					<tr ><td><b>Company Name</b></td><td>".$companyName."</td></tr>
					<tr ><td><b>Person Name</b></td><td>".$person."</td></tr>
					<tr ><td><b>Contact Number</b></td><td>".$mobile."</td></tr>
					<tr ><td><b>Email Id</b></td><td>".$emailPer."</td></tr>
					<tr ><td><b>Lead Details</b></td><td>".addslashes($_POST['discussion_summary'])."</td></tr>
					<tr ><td><b>Assigned Remarks</b></td><td>".$follow_remarks."</td></tr>
				</table>";

	$signature .= "<table >
				<tr>
					<td style='width:50%;'>
						<b>".$ccName."</b><br/>
                  		<i>".$handeledByDesignation."</i><br/>
                           <b>".$companyNameUser."</b><br/>
                          ".($address1!=''?$address1.', ':'').($address2!=''?$address2.' ':'').'<br/>'.$city.'-'.$zip."<br/>
                          M: ".$mobileCc." <br/ >Email : ".$ccEmail."
					<br><br></td>
				</tr>
			</table>";			

				

	$mailContent = "Dear ".$toName.",<br/><br/>Kindly find below the Lead Generated today for necessary action :<br/><br/>".$infoTable."<br/><br/>Kindly updated the status of the above lead in Sales Sync software to keep me posted. To update, Select <b>Today's Follow Up on your dashboad</b> and thereafter click Open button in status & update. <br/><br/>Thanks & Regards<br/>".$signature;



	// To send HTML mail, the Content-type header must be set

	$headers=array();

	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';

	// Additional headers
	//$headers[] = 'To: '.$to;

	$headers[] = 'From: '.$ccName.' <'.$ccEmail.'>';
	$headers[] = 'Reply-To: '.$ccName.' <'.$ccEmail.'>';


	$headers[] = 'Cc: '.$ccName.' <'.$ccEmail.'>';



	
	
	if($to!=$ccEmail){

		mail($to, 'You Have A New Lead', $mailContent, implode("\r\n", $headers));

	}

?>