<?php

include_once("../../config/auto_loader.php");
$to=array();
$cc=array();
$result=array();
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
	if($_REQUEST['incentive']=='inc'){
	$_REQUEST['daily_Visit_id_hidden']=encryptor('decrypt',$_REQUEST['eId']);
	
	}
	$follow_up_date='-';
	
	 $sqlQuery = "SELECT * FROM ".TBL_DAILY_ENQUERY." WHERE id='".$_REQUEST['daily_Visit_id_hidden']."' ";
		$res = mysqli_query($conn,$sqlQuery);
		$row = mysqli_fetch_object($res);
		$DateofOriginofLead = date('d-m-Y', strtotime($row->dated));
		$hotelName=selectColumn(TBL_HOTELS,'name','WHERE id="'.$row->hotel_id.'" ');
		$hotelCityName=selectColumn(TBL_HOTELS,'city','WHERE id="'.$row->hotel_id.'" ');
		$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$row->id_company.'" ' ).' -'.$companyName=selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$row->id_company.'" ' );		;		
		$person = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$row->id_contact.'" ');
		$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$row->id_contact.'" ');
		$emailPer = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$row->id_contact.'" ');
		$discussion_summary	=	$row->details;
		
		if($_REQUEST['incentive']=='inc'){
		$status_incentive_id	=$_REQUEST['status_incentive_id'];
		$id_forward_for_approval_previous=	$_REQUEST['id_user_previous'];
			
				if($status_incentive_id=='3'){
					$UserSelect	=$id_forward_for_approval_previous;	
				}else{
					$UserSelect	=$_REQUEST['id_forward_for_approval'];
					}
			
			
		 $to = selectColumn(TBL_USERS,'email','WHERE id="'.$UserSelect.'" ');
		 $toName=selectColumn(TBL_USERS,'name','WHERE id="'.$UserSelect.'" ');
		}else{
			if($_REQUEST['id_forward_for_approval']!=''){
				$UserSelect	=$_REQUEST['id_forward_for_approval'];
					
			
			
		 $to = selectColumn(TBL_USERS,'email','WHERE id="'.$UserSelect.'" ');
		 $toName=selectColumn(TBL_USERS,'name','WHERE id="'.$UserSelect.'" ');
			}else{
				$to = selectColumn(TBL_USERS,'email','WHERE id="'.$row->created_by.'" ');
				$toName=selectColumn(TBL_USERS,'name','WHERE id="'.$row->created_by.'" ');
			}
		
		}
		
		$max_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'MAX(id)',"WHERE  enquiry_id = '".$_REQUEST['daily_Visit_id_hidden']."' ");
		$assign_user_id=selectColumn(TBL_DAILY_ENQUERY_DETAILS,'assign_user_id',"WHERE  id = '".$max_id."' ");
		$ccAssignData=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$assign_user_id.'" ');
		$ccAr = explode("|",$ccAssignData);
		$ccEmail = $ccAr[0];
		$ccName =$ccAr[1];
		
		//$ids_team=selectColumn(TBL_USERS,'ids_team','WHERE id="'.$row->created_by.'" ');
		$ids_team=selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		$ids_team	=	explode(',',$ids_team);
			
		$id_user_level_1=selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team[0].'" ');	
		$ccHead=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray = explode("|",$ccHead);

		$ccHeadEmail 	   =    $ccHeadArray[0];
		$ccHeadName 		=	$ccHeadArray[1];
		
		$ccHotelEmail=selectColumn(TBL_HOTELS,'CONCAT(gm_email,"|",name)','WHERE id="'.$row->hotel_id.'" ');
		$ccHotelByArray = explode("|",$ccHotelEmail);
		$ccHotelByEmailArr 	= $ccHotelByArray[0];
		$ccHotelByNameArr 	 = $ccHotelByArray[1];
		
		$ccCreatedBy=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$row->id_user.'" ');
		$ccCreatedByArray = explode("|",$ccCreatedBy);
		$ccCreatedByEmailUser 	= ','.$ccCreatedByArray[0];
		//$ccCreatedByName 	 = $ccCreatedByArray[1];
		
		$status='Close';
		$hotelName=selectColumn(TBL_HOTELS,'name','WHERE id="'.$row->hotel_id.'" ');
		$hotelCityName=selectColumn(TBL_HOTELS,'city','WHERE id="'.$row->hotel_id.'" ');
		$followup_close_type_id= selectColumn(TBL_DAILY_ENQUERY_DETAILS,'followup_close_type_id'," WHERE `enquiry_id` = '".$row->id."'");
		$FollowupCloseTypeRemarks	=	selectColumn(TBL_CLOSING_MASTER,'name'," WHERE `id` = '".$followup_close_type_id."'");
		$follow_remarks= $FollowupCloseTypeRemarks.'-'.selectColumn(TBL_DAILY_ENQUERY_DETAILS,'enquiry_close_summary'," WHERE `enquiry_id` = '".$row->id."'");
		$discussion_summary	=$row->details;		
		$revenue	=	selectColumn(TBL_DAILY_ENQUERY_DETAILS,'revenue'," WHERE `enquiry_id` = '".$row->id."'");	
		$revenue	   ="<tr ><td><b>Revenue Generated</b></td><td>".$revenue."</td></tr>";

//incentive remarkes	
	 $incentiveSql = "SELECT * from `".TBL_INCENTIVE."` where   id_enquiry = '".$_REQUEST['daily_Visit_id_hidden']."' ";
$incentiveQuery = mysqli_query($conn,$incentiveSql);
$incentiveNumRows = mysqli_num_rows($incentiveQuery);
if($incentiveNumRows>0){
$incentiveSqlRow = mysqli_fetch_object($incentiveQuery);
$incentive_follow_up_close_summary=$incentiveSqlRow->follow_up_close_summary;

$incentiveDetailsSql = executeSql("SELECT * from `".TBL_INCENTIVE_DETAILS."` where  `id_incentive` = '".$incentiveSqlRow->id."' ORDER BY id desc ");
$incentiveDetailsRow = $db->fetch_assoc2($incentiveDetailsSql);
$incentive_remarks=	$incentiveDetailsRow['remarks'];
 $incentive_remarks_html	   ="<tr ><td><b>SLA (Sales Leads Awards) Remarks</b></td><td>".$incentive_remarks."</td></tr>";
 
 $mailSummary	=	"Kindly approve the Lead from the Activity Calendar – Sales Lead Pending Approval Button ";
}
//incentive remarkes
	
}else{ //OPEN FOLLOW UPS
	
            $DateofOriginofLead = date('d-m-Y', strtotime($_REQUEST['enquiryDate']));
            
			if($_REQUEST['followup_date']==""){
				foreach($_SESSION['followup_description'] as $dataCode =>$value){
					$follow_up_summary=$value;
					$follow_up_date=$_SESSION['followup_date'][$dataCode];
				}
			}else{
				$follow_up_date=$_REQUEST['followup_date'];
				}


	$to = selectColumn(TBL_USERS,'email','WHERE id="'.$assingToEmail.'" ');
	$toName=selectColumn(TBL_USERS,'name','WHERE id="'.$assingToEmail.'" ');
	
	
		$ids_team=selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		$ids_team	=	explode(',',$ids_team);
			
		$id_user_level_1=selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team[0].'" ');	
		$ccHead=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray = explode("|",$ccHead);

		$ccHeadEmail 	   =    $ccHeadArray[0];
		$ccHeadName 		=	$ccHeadArray[1];
		
		
		$ccCreatedBy=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$row->id_user.'" ');
		$ccCreatedByArray = explode("|",$ccCreatedBy);
		$ccCreatedByEmail 	= $ccCreatedByArray[0];
		$ccCreatedByName 	 = $ccCreatedByArray[1];


		$ccData=selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$_SESSION['userId'].'" ');
		$ccAr = explode("|",$ccData);
		$ccEmail = $ccAr[0];
		$ccName  =	$ccAr[1];
		
		$ccHotelEmail=selectColumn(TBL_HOTELS,'CONCAT(gm_email,"|",name)','WHERE id="'.$_POST['id_hotel_md'].'" ');
		$ccHotelByArray = explode("|",$ccHotelEmail);
		$ccHotelByEmailArr 	= $ccHotelByArray[0];
		$ccHotelByNameArr 	 = $ccHotelByArray[1];
		

	$status	=	'Open';
			
	$hotelName=selectColumn(TBL_HOTELS,'name','WHERE id="'.$_POST['id_hotel_md'].'" ');
	$hotelCityName=selectColumn(TBL_HOTELS,'city','WHERE id="'.$_POST['id_hotel_md'].'" ');
	
	$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_POST['id_company'].'" ' ).' -'.$companyName=selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$_POST['id_company'].'" ' );		;		;		
	$person = selectColumn(TBL_CUSTOMER,'CONCAT(title," ",first_name," ",last_name)','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$mobile = selectColumn(TBL_CUSTOMER,'mobile','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$emailPer = selectColumn(TBL_CUSTOMER,'email','WHERE id_customer="'.$_POST['id_contacts'].'" ');
	$discussion_summary	=	$_POST['discussion_summary'];
	$LeadFollowupDesc= $row->lastFollowupDesc;
		
	$mailSummary	=	"Kindly updated the status of the above lead in Sales Sync software to keep me posted. To update, <b>Go on the Follow up date in your dashboard, Click on Follow Up and thereafter click Open button in status & update. </b>";


	

	
	
} //END OPEN FOLLOW UPS
	

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
	                <tr ><td><b>Date of Origin of Lead</b></td><td>".$DateofOriginofLead."</td></tr>
					<tr ><td><b>Hotel Name</b></td><td>".$hotelName.'-'.$hotelCityName."</td></tr>
					<tr ><td><b>Company Name</b></td><td>".$companyName."</td></tr>
					<tr ><td><b>Person Name</b></td><td>".$person."</td></tr>
					<tr ><td><b>Contact Number</b></td><td>".$mobile."</td></tr>
					<tr ><td><b>Email Id</b></td><td>".$emailPer."</td></tr>
					<tr ><td><b>Lead Details</b></td><td>".addslashes($discussion_summary)."</td></tr>
					<tr ><td><b>Last Remarks</b></td><td>".$follow_remarks."</td></tr>
					<tr ><td><b>Follow Up Date</b></td><td>".$follow_up_date."</td></tr>";
			 $infoTable	.=$revenue;	
			 
			 $infoTable .="<tr ><td><b>Lead Status</b></td><td>".$status."</td></tr>";
			 $infoTable	.=$incentive_remarks_html;
				$infoTable	.="</table>";
				
				$infoTable	.=$ContentCloseMsg;
$handeledby = selectColumn(TBL_USERS,'name','where id="'.$row->modified_by.'" ');
$handeledbyEmail = selectColumn(TBL_USERS,'email','WHERE id="'.$row->modified_by.'" ');
$id_designation = selectColumn(TBL_USERS,'designation','where id="'.$row->modified_by.'" ');
$designation = selectColumn(TBL_DESIGNATION_MASTER,'name','where id="'.$id_designation.'" ');	

$sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$_SESSION['userId']."'",''));


$handeledByDesignation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$sqlUserDetail->designation.'" ');

$formalCompanyName= selectColumn(TBL_SHOP,'formal_name','WHERE id="'.$_SESSION['shop'].'" ');

$UserNameSignature=selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ');
			
$signature.="<table>
              <tr>
                  <td class='forTd' ><b>".ucwords($UserNameSignature)."</b></td>
                  
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

				
if($ccCreatedByEmail!='' && $ccCreatedByEmail!='mohit@fernhotels.com'){
			$CreatedByEmailcc	=	','.$ccCreatedByEmail;
	}
if($ccEmail!='mohit@fernhotels.com' && $ccEmail!=$ccCreatedByEmail){
    $ccEmailID	=	','.$ccEmail;
}
if($ccHeadEmail!='' && $ccHeadEmail!='mohit@fernhotels.com' && $ccHeadEmail!=$ccCreatedByEmail && $ccHeadEmail!=$ccEmail){
    $ccHeadEmailID	=	','.$ccHeadEmail;
}
if(addslashes($_SESSION['shop'])==6){
    //$MohitEmailId   ='mohit@fernhotels.com,noshir@fernhotels.com';
}
    
	$mailContent = "Dear ".$toName.",<br/><br/>";
	
//echo $mailContent .='To='.$to.'Cc: '.$ccName.' <'.$ccEmail.'>,Head='.$ccHeadName.' <'.$ccHeadEmail.'>,Created By='.$ccCreatedByEmail.' <'.$ccCreatedByName.'>';

//echo $mailContent .=	'Cc: '.$MohitEmailId.$ccEmailID.$ccHeadEmailID.$CreatedByEmailcc;
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




    
	$headers[] = 'Cc: '.$MohitEmailId.$ccEmailID.$ccHeadEmailID.$CreatedByEmailcc;

//echo $to.'=='.$ccEmail;
//die;	

	if($to!=''){
	
	
	if(addslashes($_SESSION['shop'])==6){
		$headerEmail	=explode(',',$MohitEmailId);
		if($headerEmail[0]==$to){
			$MohitEmailId   ='noshir@fernhotels.com';
		}	
    }
	
	if($ccHotelByEmailArr!=''){
	  $ccHotelByEmailArr	=	','.$ccHotelByEmailArr;
	}
		 $subject='Sales Sync - Lead';	
		
		$testEmail=',roomstatushublogs@gmail.com';	
		$cc=$MohitEmailId.$ccEmailID.$ccHeadEmailID.$CreatedByEmailcc.$ccHotelByEmailArr.$ccCreatedByEmailUser.$testEmail;
		$ReplyToName=$ccName;
		$addReplyTo=$ccEmail;
		
		//$to='roomstatushublogs@gmail.com';
		//$cc='roomstatushublogs@gmail.com';
		//echo $to.'=='.$ccEmail.$mailContent;
//die;

//echo '<br>=='.$to;echo '<br>=='.$subject;echo '<br>=='.$mailContent;echo '<br>=='.trim($cc);echo '<br>=='.$ReplyToName;echo '<br>=='.$addReplyTo;
//die;
		 $sendMail->sendMailContent('support1@roomstatushub.com', $to, $subject,$mailContent, 'roomstatushublogs@gmail.com',$ReplyToName,$addReplyTo);
		
		
		
		
		$result['status']='1';
		$result['msg']='To: '.$to.'<br/> CC Email: '.$MohitEmailId.'<br/>'.$ccEmailID.$ccHeadEmailID.$CreatedByEmailcc.'<br/>'.$ccHotelByEmailArr.$testEmail;
		echo json_encode($result);
		
		
		
	
	}

?>