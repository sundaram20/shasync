<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

/*echo "<pre>";
print_r($_REQUEST);
print_r($_SESSION);
echo "</pre>";
*/


?>


    <!-- Content Header (Page header) -->
    
    <!-- Main content -->
    <section class="content">
      <div class="row"> 
        <!-- left column -->
        <div class="col-md-12"> 
          <!-- general form elements -->
          
          <div class="nav-tabs-custom">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Daily Report: <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header --> 
            <!-- form start -->
            <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
              <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
              <input type="hidden" value="<?php echo $row->id_user;?>" name="user_id" />
              <div class="form-group has-error" align="center">
                <?php if($_SESSION['errorMsg']){?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                <?php unset($_SESSION['successMsg']);}?>
              </div>
              <div class="box-body">
              
              
             
              <div class="form-group">
               
					
<?php
	if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){$report_date	= stripslashes(date('d-m-Y',strtotime($row->dated))); }else{  $report_date	=	date('d-m-Y');}	?>
                  <label for="end_date">Report Date</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="report_date" name="report_date" value="<?php echo $report_date;?>"  data-parsley-required>
				<?php echo $err_end_date;?>
                </div>
                
                
               
                    
                <div class="form-group">
                  <label for="id_company">Company Name </label>
                  <select class="form-control" name="id_company" id="id_company" onChange="getExecutiveName(this.value,''); " data-parsley-errors-container="#companyError" data-parsley-required >
                    <option value="">Select Company</option>
                    <?php $resCat = selectSql(TBL_COMPANY," where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->id_company == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
													$companyData .= '<option  '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 echo $companyData;;
											  ?>
                  </select>
                  <span id="companyError"></span> </div>
                  
                <div class="form-group">
                  <label for="id_contacts" >Person Met</label>
                  <div class="input-group" id="showbookedby">
                  <select class="form-control select2" name="id_contacts" id="id_contacts"  data-parsley-errors-container="#contactError" >
                    <option value="">Select Person Met</option>
                  </select>
                  <span id="contactError"></span> 
                  <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>
                  </div></div>
                  
                   
               
                  
                <div class="form-group">
                  <label for="details">Business Potential</label>
                  <textarea class="form-control" name="business_potential" id="business_potential"  rows="2" placeholder="Enter business potential" automcomplete="off"><?php if($_POST) echo $_POST['business_potential'];else echo stripslashes($row->business_potential);?>
</textarea>
                  <?php echo $err_details;?> </div>
                <div class="form-group">
                  <label for="details">Discussion Summary</label>
                  <textarea class="form-control" name="discussion_summary" id="discussion_summary"    rows="2" placeholder="Enter Discussion Summary" automcomplete="off"><?php if($_POST) echo $_POST['discussion_summary'];else echo stripslashes($row->discussion_summary);?>
</textarea>
                  <?php echo $err_details;?> </div>
                 <div>
                 <label for="StatFrom">Conveyance	</label>
                 <div class="btn btn-default" style=" 	text-align: left;width: 100%;">
                 
                  <div class="col-md-2">
                  
                 
                  
                <div class="form-group">
                  <label for="StatFrom">From</label>
                  <input type="text" class="form-control" name="StatFrom" id="StatFrom"  value="<?php if($_POST) echo $_POST['StatFrom'];else echo stripslashes($row->StatFrom);?>"  placeholder="Enter From" automcomplete="off" data-parsley-required data-parsley-errors-container="#StatFromError">

                  <?php echo $err_StatFrom;?> </div>
                  </div>
                  
                  <div class="col-md-2">
                <div class="form-group">
                  <label for="StatTo">To</label>
                  <input type="text" class="form-control" name="StatTo" id="StatTo"  value="<?php if($_POST) echo $_POST['StatTo'];else echo stripslashes($row->StatTo);?>"  placeholder="Enter To" automcomplete="off" data-parsley-required data-parsley-errors-container="#StatToError">

                  <?php echo $err_StatTo;?> </div>
                  </div>

                  <div class="col-md-2">
                <div class="form-group">
                  <label for="KmsRun">Kms Run</label>
                  <input type="text" class="form-control" name="KmsRun" id="KmsRun"  value="<?php if($_POST) echo $_POST['KmsRun'];else echo stripslashes($row->KmsRun);?>"  placeholder="Enter Kms Run" automcomplete="off" data-parsley-required data-parsley-errors-container="#KmsRunError">
                  <?php echo $err_KmsRun;?> </div>
                  </div>



                  <div class="col-md-2">
                <div class="form-group">
                  <label for="RateKm">Rate/ Km </label>
                  <input type="text" class="form-control" name="RateKm" id="RateKm"  value="<?php if($_POST) echo $_POST['RateKm'];else echo stripslashes($row->RateKm);?>"  placeholder="Enter Rate / Km" automcomplete="off" data-parsley-required data-parsley-errors-container="#RateKmError">

                  <?php echo $err_RateKm;?> </div>
                  </div>


                  <div class="col-md-2">
                <div class="form-group">
                  <label for="Total">Total</label>
                  <input type="text" class="form-control" name="Total" id="Total"  value="<?php if($_POST) echo $_POST['Total'];else echo stripslashes($row->Total);?>"  placeholder="Enter Total" automcomplete="off" data-parsley-required data-parsley-errors-container="#TotaleError">

                  <?php echo $err_Total;?> </div>
                  </div>

                  <div class="col-md-2">
                <div class="form-group">
                  <label for="Parking">Parking</label>
                  <input type="text" class="form-control" name="Parking" id="Parking"  value="<?php if($_POST) echo $_POST['Parking'];else echo stripslashes($row->Parking);?>"  placeholder="Enter Parking" automcomplete="off" data-parsley-required data-parsley-errors-container="#ParkingError">

                  <?php echo $err_Parking;?> </div>
                  </div>

</div><br/><br/>
                
                <!---Follow ups--Start---------------------------------------------------->
                
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="image" style="float:left;">Follow Ups &nbsp;&nbsp; </label>
                      <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddothercharges(0);" >Add Follow Ups </button>
                    </div>
                    <?php echo $err_image;?> </div>
                  <div class="col-sm-9"> </div>
                </div>
                <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){ 
				
				
			$FollowupSql = executeSql("SELECT * from `".TBL_VISIT_FOLLOWUP."` where status='1' and  daily_Visit_id = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'");


if(num_rows($FollowupSql) > 0){

		while($FollowupSqlRow = $db->fetch_assoc2($FollowupSql)){
		
				
				
$OtherChargesuniqueCode = 'FOLLOWUPS'.rand(0000,9999);
$availableData = '<div class="btn btn-default" style=" 	text-align: left;width: 100%;"><div id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom">
<input type="hidden" name="followupCode[]" id="followupCode" value="'.$OtherChargesuniqueCode.'">';


$availableData .='<div class="form-group"><select name="followup_hotel_id['.$OtherChargesuniqueCode.']" id="followup_hotel_id|'.$OtherChargesuniqueCode.'" class="form-control select2" data-parsley-required data-parsley-errors-container="#hotelError" style="float:left;width: 100%;">
						<option value="">Select Hotel</option>';
					  $resCat_rooms = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
					  
						while($rowInclusion = $db->fetch_object2($resCat_rooms)){
							if($FollowupSqlRow['hotel_id'] == $rowInclusion->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													
							
							$availableData .= '<option '.$selected.'  value="'.$rowInclusion->id.'">'.ucfirst($rowInclusion->name).'</option>';
						
					  }
						 $availableData .= '</select></div>';
						 
						 
						 
		if($FollowupSqlRow['followup_status'] == 1){
		$selected = 'selected="selected"';
		}if($FollowupSqlRow['followup_status'] ==0){
		$selected2 = 'selected="selected"';
		}
 	 $availableData .='<div class="form-group">
                          
                           <select name="followupstatus['.$OtherChargesuniqueCode.']" id="followupstatus['.$OtherChargesuniqueCode.']" class="form-control"  data-parsley-required>
                            <option value="">Select Followup   Status</option>
							
                            <option '.$selected2.'value="0">Close</option>
                            <option '.$selected.' value="1" >Open</option>
                            
                          </select>
                         </div>';		 
						 
						 
		if($_POST) $followup_date	=	date('d-m-Y');else  $followup_date	= stripslashes(date('d-m-Y',strtotime($FollowupSqlRow['dated'])));	
				
				
					 
 $availableData .=' <div class="form-group"><input type="text" class="form-control"  name="followup_description['.$OtherChargesuniqueCode.']" id="followup_description|'.$OtherChargesuniqueCode.'" value="'.$FollowupSqlRow['followup_summary'].'"  placeholder="follow Up Summary." data-parsley-required></div>';
												 
			 $availableData .='<div class="form-group"><input type="text" class="form-control pickerdate" placeholder="Enter date" id="followup_date|'.$OtherChargesuniqueCode.'" name="followup_date['.$OtherChargesuniqueCode.']" value="'.$followup_date.'"  data-parsley-required ></div>';
			
		
						 
				  $availableData .='<div class="form-group" style="float:right;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></div>              
                </div>
				
				
				<div class="form-group">                      
                      <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddNextFollowup('.$OtherChargesuniqueCode.');" >Next Follow Ups </button>
                    </div><br><br><br>
				 <div id="AddNextFollowup_'.$OtherChargesuniqueCode.'"></div>
				</div><br><br>';
                
             echo  $availableData;   
			}
		}
                }?>
                  <div id="showOtherCharges"></div>
                
                
                <!---Follow ups--End----------------------------------------------------> 
                
                <br/> <br/> 
                 <!---FeedBack--Start---------------------------------------------------->
                
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label for="image" style="float:left;">FeedBack / Competition Summary &nbsp;&nbsp; </label>
                      <button class="pull-left btn btn-success btn-xs" type="button" onclick="ajaxAddFeedBack(1);" >Add FeedBack</button>
                    </div>
                    <?php echo $err_image;?> </div>
                  <div class="col-sm-9"> </div>
                </div>
       <?php if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){ 
				
				
			$FollowupSql = executeSql("SELECT * from `".TBL_VISIT_FEEDBACK."` where status='1' and  daily_Visit_id = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'");


if(num_rows($FollowupSql) > 0){

		while($FollowupSqlRow = $db->fetch_assoc2($FollowupSql)){     
		    
$OtherChargesuniqueCode = 'FEEDBACK'.rand(0000,9999);
$availableData = '<div class="btn btn-default" style=" 	text-align: left;width: 100%;"><div id="'.$OtherChargesuniqueCode.'" class="ajaxAddRoom">
<input type="hidden" name="feedbackCode[]"  id="feedbackCode" value="'.$OtherChargesuniqueCode.'">';


$availableData .='<div class="form-group"><select name="feedback_hotel_id['.$OtherChargesuniqueCode.']" id="feedback_hotel_id|'.$OtherChargesuniqueCode.'" class="form-control select2" data-parsley-required data-parsley-errors-container="#feedback_hotel_idError" style="float:left;width: 100%;">
						<option value="">Select Hotel</option>';
					  $resCat_rooms = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
					  
						while($rowInclusion = $db->fetch_object2($resCat_rooms)){
							if($FollowupSqlRow['hotel_id'] == $rowInclusion->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
							
							$availableData .= '<option '.$selected.' value="'.$rowInclusion->id.'">'.ucfirst($rowInclusion->name).'</option>';
						
					  }
						 $availableData .= '</select></div>';
						 
 $availableData .=' <div class="form-group"><input type="text" class="form-control"  name="feedback_description['.$OtherChargesuniqueCode.']" id="feedback_description|'.$OtherChargesuniqueCode.'" value="'.$FollowupSqlRow['feedback_summary'].'"  placeholder="FeedBack Summary." data-parsley-required></div>';
 
			if($_POST) $feedback_date	=	date('d-m-Y');else  $feedback_date	= stripslashes(date('d-m-Y',strtotime($FollowupSqlRow['dated'])));	
											 
			 $availableData .='<div class="form-group"><input type="text" class="form-control pickerdate" placeholder="Enter date" id="feedback_date|'.$OtherChargesuniqueCode.'" name="feedback_date['.$OtherChargesuniqueCode.']" value="'.$feedback_date.'"  data-parsley-required></div>';
if($FollowupSqlRow['feedback_staus'] == 1){
		$selected = 'selected="selected"';
		}if($FollowupSqlRow['feedback_staus'] ==0){
		$selected2 = 'selected="selected"';
		}
		
		
		
		
 	 $availableData .='<div class="form-group">
                          
                           <select name="feedbackstatus['.$OtherChargesuniqueCode.']" id="feedbackstatus['.$OtherChargesuniqueCode.']" class="form-control"  data-parsley-required>
                            <option value="">Select Feed Back  Status</option>
                            <option '.$selected2.'value="0">Close</option>
                            <option '.$selected.' value="1" >Open</option>
                            
                          </select>
                         </div>';
						 
				  $availableData .='<div class="form-group" style="float:right;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  id="'.$OtherChargesuniqueCode.'" onclick="ajaxOtherChargesRemove($(this).attr(\'id\'));");">
				  <i class="fa fa-trash-o fa-lg"></i> </a></div>              
                </div></div><br><br>';
                
                
      echo  $availableData;   
			}
		}?>
        
         <script>
  $( function() {	 
    $( ".datepickertest").datepicker();
  } );
  
  
               <?php }?>          
                
               

  </script>  <div id="showFeedBack"></div>
                
                
                <!---FeedBack --End----------------------------------------------------> 
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("editDailyReport.php"); '>
              </div>
            </form>
          </div>
          <!-- /.box --> 
        </div>
      </div>
      
      <!-- /.row --> 
    </section>
    <!-- /.content --> 
 