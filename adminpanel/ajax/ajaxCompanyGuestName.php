<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$companyId=$_REQUEST['companyId'];
$contactId=$_REQUEST['contactId'];
$resContact = executeSql("SELECT * from `".TBL_CUSTOMER."` where status='1' and id_company='".addslashes($companyId)."' and type='2'  order by first_name");
if(num_rows($resContact) > 0){	
	$contact  =	'<select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" >
					<option value="">Select Person</option>';
		while($rowContact = $db->fetch_object2($resContact)){	
			if($contactId==$rowContact->id_customer){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
			}
			$contact .= '<option value="'.$rowContact->id_customer.'" '.$selected.'>Name: '.$rowContact->first_name.' '.$rowContact->last_name.' | Email : '.$rowContact->email.' | Mobile : '.$rowContact->mobile.'</option>';
		}				 
		$contact .=	'</select>';
	}else{
	$contact .= '<option value="">Select User</option>';
	}
echo $contact;
?>