<?php include_once("../../config/auto_loader.php");



 $call_id	= $_REQUEST['call_id'];
 $list_id	= $_REQUEST['list'];
 $format_type = $_REQUEST['format_type'];


?>

<script>
	function chkStatus(status) {
    const followupDateGroup = document.getElementById('followupDateGroup');
    const dateInput = document.getElementById('followup_date');
	const internal_remark = document.getElementById('followup_description_class');
	const remark = document.getElementById('remark_class');
	const assign = document.getElementById('assign_followup_user_id_class');
	const close_type = document.getElementById('close_type_class');

    if (status === "0") { // Close selected
        followupDateGroup.style.display = "none";
        dateInput.value = "0000-00-00";
        dateInput.removeAttribute('data-parsley-required');
        dateInput.setAttribute('readonly', true); // block editing
		close_type.style.display = "Block";
		internal_remark.style.display = "none";
		remark.style.display="block";
		assign.style.display="none";
    } else if(status === "1"){
        followupDateGroup.style.display = "block";
        dateInput.value = ""; // Optional: clear previous value or set to today's date
        dateInput.setAttribute('data-parsley-required', 'true');
        dateInput.removeAttribute('readonly');
		internal_remark.style.display = "block";
		remark.style.display="block";
		assign.style.display="block";
		close_type.style.display ="none";
    } else {
		followupDateGroup.style.display = "none";
		internal_remark.style.display = "none";
		remark.style.display="none";
		close_type.style.display ="none";
		assign.style.display="none";
	}
}
</script>


<?php








$availableData = '<div  id="formContent" class="ajaxAddRoom"><div class="btn btn-default" style="width:100%">



<form id="AddFollowPopUpForm" name="AddFollowPopUpForm" class="AddFollowPopUpForm col-md-12" data-parsley-validate autocomplete="off">

<input type="hidden" name="call_id" id="followupCode" value="'.$call_id.'"><label>
<input type="hidden" name="list_id" id="list_id" value="'.$list_id.'"><label>
<input type="hidden" name="format" id="format" value="'.$format_type.'"><label>

<input type="hidden" name="followupCode" id="followupCode" value="1"><label>
 <input type="hidden" name="followup_type" id="followup_type" value="7">


<input type="hidden" name="FollowupCoditionType" id="FollowupCoditionType"   value="addfollowup">



Follow Ups    </label><br>

<input type="hidden" name="actionDate" value='.date('Y-m-d').'><br>

';

												 



 



 

 



$availableData .='<div class="form-group">

                          <select onChange="chkStatus(this.value);" name="followupstatus" id="followupstatus" class="form-control" data-parsley-required>



                            <option value="" selected="selected">Select Follow up Status</option>



                            <option value="0">Close</option>



                            <option value="1"  >Open</option>



                            



                          </select>







                         </div>';

    $availableData .='<div class="form-group makeHide" id="followupDateGroup" style="display:none;">
	<input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date" name="followup_date" value="'.date('d-m-Y').'"  data-parsley-required>
	</div>';                     

$availableData .=' <div class="form-group" id="followup_description_class" style="display:none;"><input type="text" class="form-control"  name="followup_description" id="followup_description|'.$OtherChargesuniqueCode.'" value=""  placeholder="Internal Remarks" maxlength="150"></lable></div>';

$availableData .= '<div class="form-group" id="close_type_class" style="display:none;"><label>Close Type</label>
<select class="form-control select2" name="close_type" id="close_type" data-parsley-required>
<option>Select Close Type</option>';

$resultClose = selectSql(TBL_CLOSING_MASTER, "WHERE status='1' AND id_shop='" . addslashes($_SESSION['shop']) . "'", ' ORDER BY name ');

while ($resultData = $db->fetch_object2($resultClose)) {
    if (!empty($row) && $row->id_closing_type == $resultData->id) {
        $selected2 = 'selected="selected"';
    } else {
        $selected2 = '';
    }

    $availableData .= '<option ' . $selected2 . ' value="' . $resultData->name . '">' . ucfirst($resultData->name) . '</option>';
}

$availableData .= '</select> </div>';


$availableData .= '	<div class="mb-3" id="remark_class" style="display:none;">
    <label for="remarks" class="form-label" >Remarks</label>
    <textarea class="form-control" id="remarks" name="remark" rows="2"></textarea>
  </div>';

    $availableData .='<div class="form-group makeHide" id="assign_followup_user_id_class" style="display:none;"><label style="float:left;">Assign To</label>';

                    $salesHead=array();



                   if($_SESSION['unit_user']=='2'){

                        $salesHead=array();

                        $teamSql = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE id_shop='".$_SESSION['shop']."'  ";

                        $teamRes= mysqli_query($connNew,$teamSql);



                        while($rowTeam=mysqli_fetch_object($teamRes)){

                          array_push($salesHead,$rowTeam->id_user_level_1);

                        }

                     }

                  

                     $availableData .= '<select class="form-control select2 chkUser" name="assign_followup_user_id" id="assign_followup_user_id" data-parsley-required>                             

					 <option value="">Select Assign UserName</option>';



                     



              $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' ",' ORDER BY `name`');

                            if($db->num_rows2($resUserLevel)){

                              while($resultUserLevel = $db->fetch_object2($resUserLevel)){

                              if($_SESSION['userId'] == $resultUserLevel->id){

                                $selected = 'selected="selected"';

                              }else{

                                $selected = '';

                              }
							$designation= selectColumn('fs_designation_master','name','WHERE id='.$resultUserLevel->designation.'  AND id_shop='.$_SESSION['shop'].' ');
                              //if($_SESSION['unit_user']=='2' && in_array($resultUserLevel->id,$salesHead) ){

                               //$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';  //
							   //-'.userTeamName($resultUserLevel->myownteam_id).
//$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'- '.$designation.'</option>';
                           //   }

                            //  else if($_SESSION['unit_user']!='2'){

                                  //$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
								  //-'.userTeamName($resultUserLevel->myownteam_id).
$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'- '.$designation.'</option>';
                           //   }



                            }

                            }

                             $availableData .= '</select>';

                                                  

                                                     $availableData .='</div>';                      

		$availableData .='<div class="form-group" style="float:left;">



		<input  type="button" class="btn btn-primary" onClick="saveAddFollowupPopUpform();" value="Save">
		<button type="button" class="btn btn-default" onclick="closePopupAndRefresh()">Close</button>

		</div>              



                </div>';



				



				



				



				$availableData .='<br><br></form></div>';



                



















echo $availableData;













exit;

?>

