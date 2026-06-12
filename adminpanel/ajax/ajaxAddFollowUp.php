<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

//include_once("../includes/header.php");
//print_r($_REQUEST);
//exit;

$followup_id	= $_REQUEST['followup_id'];

$color ='';


?>
<script>
  $( function() {	 
    $( ".datepickertest").datepicker();
  } );
  
  

  </script>
 
 
<?php
if($_REQUEST['followup_status']==0){

$OtherChargesuniqueCode = 'FOLLOWUPS'.rand(0000,9999);
$availableData = '<div  id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom"><div style="background-color:'.$color.'" class="btn btn-default" style="width:100%">
<form id="AddFollowPopUpForm" name="AddFollowPopUpForm" class="AddFollowPopUpForm" data-parsley-validate autocomplete="off">
<input type="hidden" name="followupCode[]" id="followupCode" value="'.$OtherChargesuniqueCode.'"><label>
<input type="hidden" name="FollowupCoditionType" id="FollowupCoditionType"   value="addfollowup">
<input type="hidden" name="followup_date_created['.$OtherChargesuniqueCode.']" id="followup_date_created['.$OtherChargesuniqueCode.']"  value="'.currenDateTime().'">
Follow Ups     </label><br>';



$availableData .='<div class="form-group"><select name="followup_hotel_id['.$OtherChargesuniqueCode.']" id="followup_hotel_id|'.$OtherChargesuniqueCode.'" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError" >
											    <option value="">Select Hotel</option>';
											  $resCat_rooms = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  
								while($rowInclusion = $db->fetch_object2($resCat_rooms)){
												
								$availableData .= '<option  value="'.$rowInclusion->id.'">'.ucfirst($rowInclusion->name).'-'.ucfirst($rowInclusion->city).'</option>';
												
											  }
								$availableData .= '</select></div>';
												 
 $availableData .=' <div class="form-group"><input type="text" class="form-control"  	
  name="followup_description['.$OtherChargesuniqueCode.']" id="followup_description|'.$OtherChargesuniqueCode.'" maxlength="150" value=""  placeholder="Follow Up Description." data-parsley-required > </div>';
												 
		
			 
 
$availableData .='<div class="form-group">
                          
                           <select onChange="chkStatus(this.value);" name="followupstatus['.$OtherChargesuniqueCode.']" id="followupstatus['.$OtherChargesuniqueCode.']" class="form-control " data-parsley-required>
                            <option value="">Select Follow up Status</option>
                            <option value="1">Open</option>
                            <option value="0">Close</option>
                            
                          </select>
                         </div>';
$availableData .='<div class="form-group makeHide" style="display:none;"><input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date|'.$OtherChargesuniqueCode.'" name="followup_date['.$OtherChargesuniqueCode.']" value="'.date('d-m-Y').'"  data-parsley-required></div>';                         
		
$availableData .='<div class="form-group makeHide" style="display:none;"><label style="float:left;">Assign To</label>';
               
              
                 $availableData .= '<select class="form-control select2 chkUser" name="assign_followup_user_id['.$OtherChargesuniqueCode.']" id="assign_followup_user_id['.$OtherChargesuniqueCode.']">						  								<option value="">Select Assign UserName</option>';
				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND id IN (".$_SESSION['userId'].") ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_SESSION['userId'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
												}
											  }
											 	 $availableData .= '</select>';
                                              
                                              	 $availableData .='</div>';
												
												
		$availableData .='<div class="form-group" style="float:left;">
		<input  type="button" class="btn btn-primary" onClick="saveAddFollowupPopUpform();" value="Save">
       
		<a class="btn btn-default" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxRemovefollowup($(this).attr(\'id\'));");">
				 Close </a></div>              
                </div>';
				
				
				
				$availableData .='<br><br></form></div>';
                




echo $availableData;


}else{
	
$OtherChargesuniqueCode = 'FEEDBACK'.rand(0000,9999);
$availableData = '<div id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom"><div class="btn btn-default" style="width:100%; background-color:'.$color.'" >
<form id="AddFollowPopUpForm" name="AddFollowPopUpForm" class="AddFollowPopUpForm" data-parsley-validate autocomplete="off">
<input type="hidden" name="feedbackCode[]"  id="feedbackCode" value="'.$OtherChargesuniqueCode.'">
<input type="hidden" name="FollowupCoditionType" id="FollowupCoditionType"   value="addfeedback">
<input type="hidden" name="feedback_date_created['.$OtherChargesuniqueCode.']" id="feedback_date_created['.$OtherChargesuniqueCode.']"  value="'.currenDateTime().'">
<label>
FeedBack / Competition Summary  </label><br>';


$availableData .='<div class="form-group"><select name="feedback_hotel_id['.$OtherChargesuniqueCode.']" id="feedback_hotel_id|'.$OtherChargesuniqueCode.'" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError" >
						<option value="">Select Hotel</option>';
					  $resCat_rooms = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
					  
						while($rowInclusion = $db->fetch_object2($resCat_rooms)){
							
							
							$availableData .= '<option  value="'.$rowInclusion->id.'">'.ucfirst($rowInclusion->name).'-'.ucfirst($rowInclusion->city).'</option>';
						
					  }
						 $availableData .= '</select></div>';
						 
 $availableData .=' <div class="form-group"><input type="text" class="form-control"  name="feedback_description['.$OtherChargesuniqueCode.']" id="feedback_description|'.$OtherChargesuniqueCode.'" value=""  placeholder="Feedback Description." data-parsley-required></div>';

 $availableData .='<div class="form-group " >
			 					<select data-parsley-required class="form-control" name="conclusion_type['.$OtherChargesuniqueCode.']" id="conclusion_type['.$OtherChargesuniqueCode.']">
			 						<option value="">Select Feedback Type</option>
			 						<option value="1">Positive</option>
			 						<option value="2">Negative</option>
			 					</select>
			 					</div>';

 		$availableData .='<div class="form-group" style="display:none;">
                          <label style="float:left;">Action Required</label>
                           <select onChange="chkStatus2(this.value);" name="feedbackstatus['.$OtherChargesuniqueCode.']" id="feedbackstatus['.$OtherChargesuniqueCode.']" class="form-control " data-parsley-required>
                            <option value="1">Yes</option>
                            <option selected="selected" value="0">No</option>
                            
                          </select>
                         </div>';


 		

 		$availableData .='<div class="form-group makeHide2" style="display:none;"><input type="text" class="form-control datepickertest" placeholder="Enter date" id="feedback_date|'.$OtherChargesuniqueCode.'" name="feedback_date['.$OtherChargesuniqueCode.']" value="'.date('d-m-Y').'"  data-parsley-required></div>';	
 
		 $availableData .= '<div class="form-group makeHide2" style="display:none;"><label style="float:left;">Assign To</label><select class="form-control select2" name="assign_feedback_user_id['.$OtherChargesuniqueCode.']" id="assign_feedback_user_id['.$OtherChargesuniqueCode.']">											  								<option value="">Select Assign UserName</option>';
				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND id IN (".$_SESSION['userId'].")  ",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_SESSION['userId'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
												}
											  }
											 	 $availableData .= '</select>';
                                              $availableData .='</div>';	
											  
											  
                                              	 									 
			 

			 
 
 	 
				  $availableData .='<div class="form-group" style="float:left;">
		<input  type="button" class="btn btn-primary" onClick="saveAddFollowupPopUpform();" value="Save">
       
		<a class="btn btn-default" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxRemovefollowup($(this).attr(\'id\'));");">
				 Close </a></div>              
                </div><br><br></form></div>';
echo $availableData;
}
?>
                




