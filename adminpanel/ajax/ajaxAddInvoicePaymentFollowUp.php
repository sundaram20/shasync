<?php include_once("../../config/auto_loader.php");



/////////////////////////////////////////////////////////////////////////////////////////////////////







//include_once("../includes/header.php");



$followup_id	= $_REQUEST['followup_id'];



?>



<script>



  $( function() {	 



     $( ".datepickertest").datepicker({dateFormat: 'dd-mm-yy',minDate: 0});



  } );

</script>



 



 



<?php



if($_REQUEST['followup_status']==0){





$OtherChargesuniqueCode = 'FOLLOWUPS'.rand(0000,9999);



$availableData = '<div  id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom"><div class="btn btn-default" style="width:100%">



<form id="AddFollowPopUpForm" name="AddFollowPopUpForm" class="AddFollowPopUpForm col-md-12" data-parsley-validate autocomplete="off">



<input type="hidden" name="followupCode[]" id="followupCode" value="'.$OtherChargesuniqueCode.'"><label>



<input type="hidden" name="FollowupCoditionType" id="FollowupCoditionType"   value="addfollowup">



Payment - Follow Ups    </label><br>

<input type="hidden" name="actionDate" value='.date('Y-m-d').'><br>

';

												 



 



 

 



$availableData .='<div class="form-group">

                          <select onChange="chkStatus(this.value);" name="followupstatus['.$OtherChargesuniqueCode.']" id="followupstatus['.$OtherChargesuniqueCode.']" class="form-control" data-parsley-required>



                            <option value="">Select Payment Status</option>


							<option value="1" selected="selected" >Pending</option>
                            <option value="0">Received</option>


							<option value="2">Parcially Received</option>
                            

							

                            



                          </select>







                         </div>';

    $availableData .='<div class="form-group makeHide" ><input type="text" class="form-control datepickertest" placeholder="Enter date" id="followup_date|'.$OtherChargesuniqueCode.'" name="followup_date['.$OtherChargesuniqueCode.']" value="'.date('d-m-Y').'"  data-parsley-required></div>';   
	
	
	
	
	  $sql = "  SELECT * FROM `".INVOICE."`
								WHERE `id` = '".addslashes($_REQUEST['edit_id_enquiry'])."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}                  
 $availableData .='<div class="form-group col-md-4">
              

              <label for="invoiceno">Amount:</label>
              <input type="text" class="form-control " placeholder="Enter Amount " id="new_amount1" name="new_amount1" value="'.$row->amount.'"  data-parsley-required readonly="readonly">
              </div>';    
                  
                $availableData .='
  <div class="form-group col-md-4">
              

              <label for="invoiceno">Received:</label>
              <input type="text" class="form-control " onkeyup="amount_calc(this.value,2);" placeholder="Enter Received Amount" id="new_received1" name="new_received1" value="'.$row->received.'"  data-parsley-required>
               </div>';  
              
              
             $availableData .=' <div class="form-group col-md-4">
              

              <label for="invoiceno">Balance :</label>
              <input type="text" class="form-control " placeholder="Enter Balance" id="new_balance1" name="new_balance1" value="'.$row->balance.'"  data-parsley-require readonly="readonly">
              </div>';





$availableData .=' <div class="form-group"><input type="text" class="form-control"  name="followup_description['.$OtherChargesuniqueCode.']" id="followup_description|'.$OtherChargesuniqueCode.'" value=""  placeholder="Remarks" maxlength="150"></lable></div>';



    $availableData .='<div class="form-group makeHide" ><label style="float:left;">Assign To</label>';

                    $salesHead=array();



                   if($_SESSION['unit_user']=='2'){

                        $salesHead=array();

                        $teamSql = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE id_shop='".$_SESSION['shop']."'  ";

                        $teamRes= mysqli_query($connNew,$teamSql);



                        while($rowTeam=mysqli_fetch_object($teamRes)){

                          array_push($salesHead,$rowTeam->id_user_level_1);

                        }

                     }

                  

                     $availableData .= '<select class="form-control select2 chkUser" name="assign_followup_user_id['.$OtherChargesuniqueCode.']" id="assign_followup_user_id['.$OtherChargesuniqueCode.']" data-parsley-required>                             

					 <option value="">Select Assign UserName</option>';



                     



              $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND `sales_status_active` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."' AND user_type!=2  ",' ORDER BY `name`');

                            if($db->num_rows2($resUserLevel)){

                              while($resultUserLevel = $db->fetch_object2($resUserLevel)){

                              if($_SESSION['userId'] == $resultUserLevel->id){

                                $selected = 'selected="selected"';

                              }else{

                                $selected = '';

                              }
							$designation= selectColumn('fs_designation_master','name','WHERE id='.$resultUserLevel->designation.'  AND id_shop='.$_SESSION['shop'].' ');
                              if($_SESSION['unit_user']=='2' && in_array($resultUserLevel->id,$salesHead) ){

                               //$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';  //
							   //-'.userTeamName($resultUserLevel->myownteam_id).
$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'- '.$designation.'</option>';
                              }

                              else if($_SESSION['unit_user']!='2'){

                                  //$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
								  //-'.userTeamName($resultUserLevel->myownteam_id).
$availableData .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'- '.$designation.'</option>';
                              }



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











}

exit;

?>

