<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$countryId=$_REQUEST['countryId'];
$stateId=$_REQUEST['stateId'];
$otherState=$_REQUEST['otherState'];
$resState = executeSql("SELECT * from `".TBL_STATE."` where status='1' and id_country='".addslashes($countryId)."' order by name");
if(num_rows($resState) > 0){	
	$state  =	'<select class="form-control select2" name="id_state" id="id_state" data-parsley-errors-container="#stateError" data-parsley-required >
					<option value="">Select State</option>';
		while($rowState = $db->fetch_object2($resState)){	
			if($stateId==$rowState->id_state){
			$selected = 'selected="selected"';
			}else {
			$selected = '';
			}
			$state .= '<option value="'.$rowState->id_state.'" '.$selected.'>'.$rowState->name.'</option>';
		}				 
		$state .=	'</select>';
	}else {	
		$state = '<input type="text" disabled="disabled" name="other_state" id="id_state" class="form-control" value="'.$otherState.'" placeholder="Enter State" data-parsley-errors-container="#stateError" automcomplete="off">';
	}	
echo $state;
?>