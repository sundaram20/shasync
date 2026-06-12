<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$season = $_REQUEST['seasonId'];
//////////////////////////////getting and showing season date//////////////////////////////////////////////////////

	
	 $StartDate	=	 $_REQUEST['start_date'];
	
	$rate_level_id	= selectColumn(TBL_RATE,'rate_level_id'," WHERE `id` = '".$_REQUEST['SelectedRateID']."'"); 
	
	$resContact = executeSql("SELECT * from `".TBL_RATE_LEVEL."` where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
if(num_rows($resContact) > 0){	
	$contact  =	'<select class="form-control select2" name="new_rate_level_id_change" id="new_rate_level_id_change" >
					<option value="">Select Rate Level</option>';
		while($rowContact = $db->fetch_object2($resContact)){	
			if($rate_level_id==$rowContact->id){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
			}
			$contact .= '<option value="'.$rowContact->id.'" '.$selected.'>'.$rowContact->name.'</option>';
		}				 
		$contact .=	'</select>';
	}else{
	$contact .= '<option value="">Select Rate Level</option>';
	}

echo $contact.'&&&&'.$rate_level_id;



$market_id	= selectColumn(TBL_RATE,'market'," WHERE `id` = '".$_REQUEST['SelectedRateID']."'"); 

	$resContact_1 = executeSql("SELECT * from `".TBL_RATE_MARKET."` where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
if(num_rows($resContact) > 0){	
	$contact_1  =	'<select class="form-control select2" name="market" id="market" data-parsley-errors-container="#marketError" >
					<option value="">Select Rate Level</option>';
		while($rowContact_1 = $db->fetch_object2($resContact_1)){	
			if($market_id==$rowContact_1->id){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
			}
			$contact_1 .= '<option value="'.$rowContact_1->id.'" '.$selected.'>'.$rowContact_1->name.'</option>';
		}				 
		$contact_1 .=	'</select>';
	}else{
	$contact_1 .= '<option value="">Select User</option>';
	}

echo '|||'.$contact_1.'&&&&'.$market_id;


?>
