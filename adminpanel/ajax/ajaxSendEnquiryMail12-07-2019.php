<?php

	include_once("../../config/auto_loader.php");
	
	
		$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
		$sqlQuery = "SELECT * FROM ".TBL_DAILY_ENQUERY." WHERE id='".encryptor('decrypt',$_REQUEST['eId'])."' ";
		$res = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_object($res);


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

	



	

	
if($_REQUEST['FollowupClose']=='close'){
	
	$sqlQuery = "SELECT * FROM ".TBL_DAILY_ENQUERY." WHERE id='".$_REQUEST['daily_Visit_id_hidden']."' ";
		$res = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_object($res);
		$hotelName=selectColumn(TBL_HOTELS,'name','WHERE id="'.$row->hotel_id.'" ');
		$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$row->id_company.'" ' );		
		$person = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$row->id_contact.'" ');
		$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$row->id_contact.'" ');
		$emailPer = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$row->id_contact.'" ');
		$discussion_summary	=	$row->details;
		
		$to = selectColumn(TBL_USERS,'email','WHERE id="'.$row->created_by.'" ');
		$toName=selectColumn(TBL_USERS,'name','WHERE id="'.$row->created_by.'" ');
		
		
		$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$_REQUEST['daily_Visit_id_hidden']."' ");
		$assign_user_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'assign_user_id',"WHERE  id = '".$max_id."' ");
		$ccAssignData=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$assign_user_id.'" ');
		$ccAr = explode("|",$ccAssignData);
		$ccEmail = $ccAr[0];
		$ccName =$ccAr[1];
		
		
		$ids_team=selectColumn(TBL_USERS,'ids_team','WHERE id="'.$row->created_by.'" ');
		$ids_team	=	explode(',',$ids_team);
		
		$ids_team=selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		$id_user_level_1=selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team.'" ');	
		$ccHead=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray = explode("|",$ccHead);

		$ccHeadEmail 	   =    $ccHeadArray[0];
		$ccHeadName 		=	$ccHeadArray[1];
	
		/*$assign_user_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'assign_user_id','WHERE enquiry_id="'.$_SESSION['userId'].'" and   type=4 ORDER BY id desc');
		$ccData=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$assign_user_id.'" ');	
		$ccAr = explode("|",$ccData);
		$ccEmail = $ccAr[0];
		$ccName =$ccAr[1];*/
		
		
		$status='Close';
		$hotelName=selectColumn(TBL_HOTELS,'name','WHERE id="'.$row->hotel_id.'" ');
		$followup_close_type_id= selectColumn(TBL_DAILY_ENQUERY_DETAILS,'followup_close_type_id'," WHERE `enquiry_id` = '".$row->id."'");
		$FollowupCloseTypeRemarks	=	selectColumn(TBL_CLOSING_MASTER,'name'," WHERE `id` = '".$followup_close_type_id."'");
		$follow_remarks= $FollowupCloseTypeRemarks.'-'.selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");
		$discussion_summary	=$row->details;			   

}else{
	$to = selectColumn(TBL_USERS,'email','WHERE id="'.$assingToEmail.'" ');
	$toName=selectColumn(TBL_USERS,'name','WHERE id="'.$assingToEmail.'" ');
	
	
	$ids_team=selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		$id_user_level_1=selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team.'" ');	
		$ccHead=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray = explode("|",$ccHead);

	//$ccHeadEmail = $ccHeadArray[0];

	//$ccHeadName 	=	$ccHeadArray[1];
		
		

$ccData=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$_SESSION['userId'].'" ');



	$ccAr = explode("|",$ccData);

	$ccEmail = $ccAr[0];

	$ccName 	=	$ccAr[1];

	$status	=	'Open';
			
	$hotelName=selectColumn(TBL_HOTELS,'name','WHERE id="'.$_POST['id_hotel_md'].'" ');
	$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_POST['id_company'].'" ' );		
	$person = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$emailPer = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$discussion_summary	=	$_POST['discussion_summary'];
	$LeadFollowupDesc= $row->lastFollowupDesc;
		
	$mailSummary	=	"Kindly updated the status of the above lead in Sales Sync software to keep me posted. To update, Select <b>Today's Follow Up on your dashboad</b> and thereafter click Open button in status & update.";
}
	

	$companyNameUser=selectColumn(TBL_USERS,'company','WHERE id="'.$_SESSION['userId'].'" ');

	$id_designation = selectColumn(TBL_USERS,'designation','WHERE id="'.$_SESSION['userId'].'" ');

	$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');

	$address1 = selectColumn(TBL_USERS,'address','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');
	
	$address2 = selectColumn(TBL_USERS,'address2','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');

	$city = selectColumn(TBL_USERS,'city','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');

	$zip = selectColumn(TBL_USERS,'zip','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');
	$phone = selectColumn(TBL_USERS,'phone','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');

	$mobileCc = selectColumn(TBL_USERS,'mobile','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"');



	$infoTable="<table style='margin-bottom:20px;' border='1' cellspacing='0'>
	               <tr style='text-align:center;'><td colspan='2'><b>LEAD</b></td></tr> 
					<tr ><td><b>Hotel Name</b></td><td>".$hotelName."</td></tr>
					<tr ><td><b>Company Name</b></td><td>".$companyName."</td></tr>
					<tr ><td><b>Person Name</b></td><td>".$person."</td></tr>
					<tr ><td><b>Contact Number</b></td><td>".$mobile."</td></tr>
					<tr ><td><b>Email Id</b></td><td>".$emailPer."</td></tr>
					<tr ><td><b>Lead Details</b></td><td>".addslashes($discussion_summary)."</td></tr>
					<tr ><td><b>Last Remarks</b></td><td>".$follow_remarks."</td></tr>
					<tr ><td><b>Status</b></td><td>".$status."</td></tr>
				</table>";
				
				
$handeledby = selectColumn(TBL_USERS,'name','where id="'.$row->modified_by.'" ');
$handeledbyEmail = selectColumn(TBL_USERS,'email','WHERE id="'.$row->modified_by.'" ');
$id_designation = selectColumn(TBL_USERS,'designation','where id="'.$row->modified_by.'" ');
$designation = selectColumn(TBL_DESIGNATION_MASTER,'name','where id="'.$id_designation.'" ');	

$sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->modified_by."'",''));


$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$sqlUserDetail->designation.'" ');

$formalCompanyName= selectColumn(TBL_SHOP,'formal_name','WHERE id="'.$_SESSION['shop'].'" ');

			
$signature.="<table>
              <tr>
                  <td class='forTd' ><b>".ucwords($ccName)."</b></td>
                  
              </tr>
              <tr>
                <td class='forTd' ><b>".ucwords($handeledByDesignation)."</b><br></td>
              </tr>
          </table>";
		  
		  
$signature .= "<table style='width : 100%;'>
        <tr><td></td></tr>
        </tr>
            <td ><span style='color:green;font-weight:bold;'>".$companyNameUser."</span>
			<span style='font-family:Georgia !imporant;font-size:9pt !imporant;'><br>".trim($formalCompanyName)."<br/>
                ".($address1!=''?$address1.', ':'').($address2!=''?$address2.' ':'').trim($city).'-'.$zip."<br>
                M: ".$mobileCc." | T: ".$phone." | Email : ".$ccEmail."</span>               
            </td>
        </tr>
      </table>";
	  		  
	/*$signature .= "<table >
				<tr>
					<td style='width:50%;'>
						<b>".$ccName."</b><br/>
                  		<i>".$handeledByDesignation."</i><br/>
                           <b>".$companyNameUser."</b><br/>
                          ".($address1!=''?$address1.', ':'').($address2!=''?$address2.' ':'').'<br/>'.$city.'-'.$zip."<br/>
                          M: ".$mobileCc." <br/ >Email : ".$ccEmail."
					<br><br></td>
				</tr>
			</table>";	*/		

				

	$mailContent = "Dear ".$toName.",<br/><br/>";
	
	//$mailContent .='To='.$to.'Cc: '.$ccName.' <'.$ccEmail.'>,Head='.$ccHeadName.' <'.$ccHeadEmail.'>';
	
	$mailContent .=$infoTable."<br/><br/>";
	
	$mailContent .=$mailSummary; 
	
	$mailContent .="<br/><br/>Thanks & Regards<br/>".$signature;
	
	
	$headers=array();

	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';

	// Additional headers
	//$headers[] = 'To: '.$to;

	$headers[] = 'From: RoomStatusHUB <support@roomstatushub.com>';
	$headers[] = 'Reply-To: '.$ccName.' <'.$ccEmail.'>';


	$headers[] = 'Cc: '.$ccName.' <'.$ccEmail.'>,'.$ccHeadName.' <'.$ccHeadEmail.'>';



	
	
	if($to!=$ccEmail){
	mail($to, 'Sales Sync - Lead', $mailContent, implode("\r\n", $headers));	
	//mail('support1@roomstatushub.com', 'Sales Sync - Lead', $mailContent, implode("\r\n", $headers));	
	}

?>