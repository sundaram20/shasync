<?php include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');
/////////////////////////////////////////////////////////////////////////////////////
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	//$sql = "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where b.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";
	$db->query($sql);
	if($db->num_rows() > 0){	
	
		$row = $db->fetch_object();
	}						
}	
	
if($_REQUEST['id'] !=''){
	$disabled = 'disabled="disabled"';
}						


?>
<?php include_once("includes/header.php")?>
  <?php include_once("includes/left.php"); ?>
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Rate Manager <small>Rate Master</small> </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Rate Master</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
    <div class="row"> 
      <!-- left column -->
      <div class="col-md-12">
      <!-- general form elements -->
      <div class="nav-tabs-custom">
      <?php

?>
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Rate <?php echo $row->rate_name.'-'.$row->sub_code;  ?> </h3>
      </div>
      <!-- /.box-header --> 
      <!-- form start -->
      <div class="form-group has-error" align="center" >
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <form name="rateMaster" id="rateMaster"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
        <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" id="id" />
         <input type="hidden" value="0" name="company_id" id="company_id" />
        <div class="box-header">
          <div class="row">
          <?php /*?>  <div class="form-group col-sm-3">
            <label for="seasonId">Company <font color="#FF0000">*</font></label>
            <select class="form-control select2" name="company_id" id="company_id" data-parsley-errors-container="#companyError" <?php //echo $disabled; ?> onChange="getCompanyGuestName(this.value,'');" >
              <option value="0">Not Applicable</option>
              ';
								 
              <?php			  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id_company == $row->company_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}	
													$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $companyDropDown .= '</select>';
											  ?>
              <span id="companyError"><?php echo $err_company;?></span>
              </div>
              <?php   
                  
         if($_REQUEST['id'] !=''){
			 
			 
		 $resContact = executeSql("SELECT * from `".TBL_CUSTOMER."` where status='1' and id_company='".addslashes($row->company_id)."' and type='2' order by first_name");?>
              <div class="form-group col-sm-8" >
              <label for="id_contacts" >
              Name
              </label>
              <div class="input-group" id="showContact">
              <select class="form-control" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError" <?php //echo $disabled; ?>>
              <option value="">Booking By</option>
              ';
		
              <?php while($rowContact = $db->fetch_object2($resContact)){	
			if($row->id_contacts==$rowContact->id_customer){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
				
			}
			?>
              <option value="<?php $rowContact->id_customer; ?>" <?php echo $selected?> ><?php echo 'Name : '.ucfirst($rowContact->first_name).' '.ucfirst($rowContact->last_name) .' | Email : '.$rowContact->email.' | Mobile : '.$rowContact->mobile ?></option>
              <?php 		}				 ?>
            </select>
            <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i></div>
          </div>
        </div>
        <?php 
}else{  			 ?>
        <div class="form-group col-sm-8" >
          <label for="id_contacts" >Name</label>
          <div class="input-group" id="showbookedby">
            <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#contactError">
              <option value="">Select User</option>
            </select>
            <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>
          </div>
          <span id="contactError"></span> </div>
        <?php } ?><?php */?>
        <?php
	  
	   if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){  ?>
        <div class="form-group col-sm-3">
          <label for="rate_level_id">Rate category<font color="#FF0000">*</font></label>
          <select class="form-control" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="rateMasterFunction();" <?php echo $disabled; ?>>
            <option value="">Select Rate Level</option>
            <?php $resCat = selectSql(TBL_RATE_LEVEL," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $row->rate_level_id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$availableData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 echo $availableData;;
											  ?>
          </select>
          <span id="rate_level_idError"></span> </div>
          
          
        <?php }else{ ?>
       
       
        <div class="form-group col-sm-3">
          <label for="rate_level_id">Rate category<font color="#FF0000">*</font></label>
          <select class="form-control" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="rateMasterFunction();" <?php echo $disabled; ?>>
            <option value="">Select Rate Level</option>
            <?php $resCat = selectSql(TBL_RATE_LEVEL," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');
                if($db->num_rows2($resCat)){
                while($resultCat = $db->fetch_object2($resCat)){
                if($resultCat->id == $row->rate_level_id){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
                $availableData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                }
                }
                echo $availableData;;
                ?>
          </select>
          <span id="rate_level_idError"></span> </div>
          
        <?php } ?>
        
        
        
        <div class="form-group col-sm-3">
          <label for="seasonId">Season<font color="#FF0000">*</font></label>
          <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" data-parsley-errors-container="#seasonError" onchange="getseasonDate(this.value); rateMasterFunction();" data-parsley-required  '.$disabled.'>
											  <option value="">Select Season</option>';
											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $row->seasonId){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}	
													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $seasonDropDown .= '</select>';
											  ?>
          <span id="seasonError"><?php echo $err_season;?></span> </div>
        <div class="form-group col-sm-2">
          <label for="start_date">Start Date</label>
          <div class="input-group">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input type="text" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" <?php // echo $disabled; ?> >
            <input type="hidden" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" >
          </div>
          <!-- /.input group --> 
          <span id="start_dateError"><?php echo $err_start_date;?></span> </div>
        <div class="form-group col-sm-2">
          <label for="end_date">End Date </label>
          <div class="input-group">
            <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
            <input type="text" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  <?php // echo $disabled; ?> >
            <input type="hidden" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  >
          </div>
          <!-- /.input group --> 
          <span id="end_dateError"><?php echo $err_end_date;?></span> </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-3">
            <label for="hotelId">Hotel <font color="#FF0000">*</font></label>
            <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" data-parsley-errors-container="#hotelError" onchange="getRoom(this.value,1); rateMasterFunction(); getInclusionDetail(this.value);" data-parsley-required >
												  <option value="">Select Hotel</option>';
												  
												  if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_HOTELS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';
													}
												  }
													echo $hotelDropDown .= '</select>';
												  ?>
            <span id="hotelError"><?php echo $err_hotel;?></span> </div>
          <div class="form-group col-sm-3">
            <label for="remarks">Market <font color="#FF0000">*</font></label>
            <?php $marketDropDown = '<select class="form-control  select2 input-sm" name="market" id="market" data-parsley-errors-container="#marketError" onchange="rateMasterFunction();" data-parsley-required '.$disabled.'>
												  <option value="">Select Market</option>';
												 
												  $resCat = selectSql(TBL_RATE_MARKET," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');										
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->market){
															$selected = 'selected="selected"';
														}else if($_REQUEST['market']== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$marketDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $marketDropDown .= '</select>';
												  ?>
            <span id="marketError"><?php echo $err_market;?></span> </div>
          <div class="form-group col-sm-4">
            <label for="remarks">Remarks </label>
            <textarea class="form-control " name="remarks" id="remarks" rows="1" placeholder="Enter Remarks" automcomplete="off" ><?php echo $row->remarks; ?></textarea>
          </div>
          <div class="clearfix"></div>
          <?php 
					
					/*$inclusionDetail= json_decode($rowInclusion->inclusion_detail,true);
					
					$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and type='1' order by display_order"); 
				 
				 	while($rowRateInclusion = $db->fetch_object2($resRateInclusion)){
					
					if($inclusionDetail[$rowRateInclusion->id]!=''){
						$inclusionValue = $inclusionDetail[$rowRateInclusion->id];
					}else{
						$inclusionValue = 0;
					}
					
					echo ' <div class="form-group col-sm-2"> 
					  <label for="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'">'.$rowRateInclusion->name.'</label> 
					  <input type="hidden" name="inclusion_id[]" value="'.$rowRateInclusion->id.'" />
					  <input type="text" class="form-control inclusionFood" placeholder="Enter '.$rowRateInclusion->name.' Price" id="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'" name="inclusion_detail[]" value="'.$inclusionValue.'" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);"> 
                  </div> ';
					
					}*/
				 
				 ?>
          <div class="clearfix"></div>
          <?php /*?><div class="form-group col-sm-2">
                  <label for="extra_bed">Extra Bed	</label>                 
                  <input type="text" class="form-control extra_bed" placeholder="Enter extra bed price" id="extra_bed" name="extra_bed" value="0" data-parsley-required data-parsley-type="digits" onkeyup="rateExtra(this.id,this.value);">
                </div><?php */?>
          <div class="form-group col-sm-1" style="display:none;">
            <label for="extras">Extras</label>
            <input type="hidden" name="inclusion_id[]" value="0" />
            <input type="hidden" name="inclusion_extra" id="inclusion_extra" value="0" />
            <input type="text" class="form-control inclusionExtra" placeholder="Enter extras price" id="extras" name="inclusion_detail[]" value="<?php if($inclusionDetail['0']!=''){echo $inclusionDetail['0'];}else{echo '0';} ?>" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
          </div>
          <?php $data= json_decode($row->rate_points,true); ?>
        </div>
        </div>
      </form>
		  
		  
      <form name="form1" id="rateUpdate"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
        <div class="box-body no-padding text-center loading" >
          <button type="button" class="btn btn-default btn-lrg ajax" title="Loading..."> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
        </div>
        <div class="box-body box-primary" id="rateMasterDetail"> </div>
        
        <!-- /.box-body -->
        <div style="float: left;width: 1100px;">
          <div class="form-group col-sm-4">
            <label for="userlevelId">Rate Points</label>
            <select class="form-control select2" name="rate_points[]"  id="rate_points" multiple="multiple" data-parsley-errors-container="#rate_pointsError">
              <?php 
					$sqlUserActions = selectSql(TBL_RATE_POINTS," where  status='1' and id_shop='".$_SESSION['shop']."' ",'');
					$iCounterActions = 0;
					while($resUserActions = $db->fetch_object2($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_RATE."` WHERE FIND_IN_SET('".$resUserActions->id."',rate_points ) and id='".addslashes(encryptor('decrypt',$_REQUEST['id']))."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->title.'</option>';
						
						$iCounterActions++;
					}
					?>
            </select> 
            <?php echo $err_rate_points;?> </div>
          <div class="form-group col-sm-4">
            <label for="id_guest" >General Terms</label>
            <div class="input-group" id="showGuest">
              <select class="form-control " name="generalterms" id="generalterms" data-parsley-errors-container="#generaltermsError">
                <option value="">General Terms</option>
                <?php 
		$resCat = selectSql(TBL_GENERAL_TERMS,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ");
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($row->generalterms == $resultCat->id){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->title.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
              </select>
            </div>
            <span id="generaltermsError"></span> </div>
        </div>
        <div class="form-greoup" style="margin: 15px;">
          <label for="additional_points" style="width:100%;">Additional Points</label>
          <textarea id="additional_points" name="additional_points" rows="10" cols="80"><?php if($_POST) echo $_POST['additional_points'];else echo stripslashes($row->additional_points);?>
</textarea>
          <?php echo $err_additional_points;?> </div>
        <br/>

        <?php if($row->date_created){?>
             
           <div class="form-group col-sm-4">
                     <label for="date_created">Date Created</label>
                     <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">        
                   </div> 
           
           <div class="form-group col-sm-4">
                     <label for="last_modified">Last Updated</label>
                     <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">       
                   </div> 
           
           <div class="form-group col-sm-4">
                     <label for="last_modified_by">Last Updated By</label>
              <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                     <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">       
                   </div>  
             
             <?php } ?>  
         <div class="form-group col-sm-12">
                  <label for="booking_status">Allow For Booking</label>
                 <input type="radio"  class="flat-red" <?php if($_POST['allow_booking'] == '1'){echo "checked";}else{if($row->allow_booking == 1)echo "checked";}?> value="1" name="allow_booking"/> Yes
				 <input type="radio" class="flat-red" <?php if($_POST['allow_booking'] == '0'){echo "checked";}else{if($row->allow_booking == 0)echo "checked";}?> value="0" name="allow_booking"/> No
				 <?php echo $err_booking_status;?>
                </div> 
                
                
        <div class="form-group col-sm-12">
                  <label for="status">Status</label>
                 <input type="radio"  class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
            
        <div class="box-footer ">
          <input type='hidden' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
          <input type='button' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" onclick="submitRateForm();"  >
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRateMaster.php?page=<?php echo $_GET['page']; ?>"); '>
        </div>
        
        
        
      </form>
    </div>
    <!-- /.box --> 
  </div>
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>

<!--save data in popup-->
<div id="fadeandscale" class="well">
  <form id="popupForm" >
    <input type="hidden" id="roomEid" >
    <input type="hidden" id="ratePlanId" >
    <input type="hidden" id="tarrifId" >
    <input type="hidden" id="tarrifName" >
    <input type="hidden" id="planType" >
    <div class="form-group">
      <label for="tarrif">Tarrif Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter tarrif price" id="tarrif" name="tarrif" value="0" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group">
      <label for="meal">Meal Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter meal price" id="meal" name="meal" value="0" data-parsley-required data-parsley-type="digits" readonly="readonly">
    </div>
    <button class="fadeandscale_close btn btn-default" onclick="SavePopup();">Save</button>
    <button class="fadeandscale_close btn btn-default">Close</button>
  </form>
</div>

<!--show msg in popup--> 
<span class="my_popup_open" style="display:none;"></span>
<div id="my_popup" class="well">
  <div id="rateUpdateData"></div>
  <button class="my_popup_close btn btn-default pull-right">Close</button>
</div>

<!--show msg in popup-->
<div id="ratePoint" class="well" style="display:none;">
  <form id="ratePointForm" autocomplete="off">
    <div id="ratePoinData"></div>
  </form>
  <button class="ratePoint_close btn btn-primary pull-right" onclick="SaveRatePointPopup();">Add</button>
</div>

<!--create pkg popup for ----->
<div id="pkgPopup" class="well" style="display:none; max-width:44em;">
  <form id="pkgpopupForm" autocomplete="off">
    <input type="hidden" id="pkgroomEid" >
    <input type="hidden" id="pkgratePlanId" >
    <input type="hidden" id="pkgplanType" >
    <div class="form-group col-sm-6">
      <label for="pkg_title">Title</label>
      <input type="text" class="form-control input-sm" placeholder="Enter title" id="pkg_title" name="pkg_title"  data-parsley-required >
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_description">Description</label>
      <textarea type="text" class="form-control input-sm" rows="1" placeholder="Enter description" id="pkg_description" name="pkg_description"  data-parsley-required ></textarea>
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_min_pax">Min. Pax</label>
      <input type="text" class="form-control input-sm" placeholder="Enter min pax" id="pkg_min_pax" name="pkg_min_pax" value="2" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_min_nights">Min. Nights</label>
      <input type="text" class="form-control input-sm" placeholder="Enter min nights" id="pkg_min_nights" name="pkg_min_nights" value="2" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group col-sm-6">
      <label for="rack_rate">Rack Rate</label>
      <input type="text" class="form-control input-sm" placeholder="Enter double pax price" id="rack_rate" name="rack_rate" value="0" data-parsley-required data-parsley-type="digits" readonly="true">
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_discount">Discount</label>
      <div class="input-group">
        <input type="text" class="form-control input-sm" id="pkg_discount" name="pkg_discount" value="0" data-parsley-required automcomplete="off" data-parsley-type="digits" data-parsley-maxlength="2">
        <span class="input-group-addon" ><i class="fa fa-percent"></i></span></div>
    </div>
    <div class="form-group col-sm-6">
      <label for="pkg_extra_price">Extra Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter package pax price" id="pkg_extra_price" name="pkg_extra_price" value="0" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group col-sm-6" style="margin-top:30px;">
      <label for="pkg_status">Status &nbsp;</label>
      <input type="radio" value="1" name="pkg_status" id="pkg_status1"/>
      Active
      <input type="radio" value="0" name="pkg_status" id="pkg_status2"/>
      Inactive </div>
    <div class="form-group col-sm-12" align="center">
      <button class="btn btn-primary" onclick="savePkgPopupData();" type="button">Save</button>
      <button class="pkgPopup_close btn btn-default" onclick="this.form.reset();">Close</button>
    </div>
  </form>
</div>
<div id="bookedby" class="well">
  <form id="bookedbypopupform" data-parsley-validate autocomplete="off" method="post"  >
    <div class="form-group">
      <label for="first_name">First Name </label>
      <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required data-parsley-type="alphanum">
    </div>
    <div class="form-group">
      <label for="last_name">Last Name</label>
      <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required>
    </div>
    <div class="form-group">
      <label for="email" >Email Id</label>
      <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Id" data-parsley-type="email" automcomplete="off">
    </div>
    <div class="form-group">
      <label for="mobile" >Mobile No.</label>
      <input type="phone" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number"  data-parsley-type="digits" data-parsley-length="[10, 10]" automcomplete="off">
    </div>
    <input  type="button" class="btn btn-default" onClick="saveRateCustomerPopupform();" value="Save">
    <button class="bookedby_close btn btn-default">Close</button>
  </form>
</div>

<!--create new pkg row popup  for add --- currently hidden -->
<div id="pkg" class="well" style="max-width:44em;">
  <form id="pkgForm" autocomplete="off">
    <input type="hidden" id="pkgId" value="">
    <div class="form-group col-sm-6">
      <label for="title">Title</label>
      <input type="text" class="form-control input-sm" placeholder="Enter title" id="title" name="title" value="" data-parsley-required >
    </div>
    <div class="form-group col-sm-6">
      <label for="description">Description</label>
      <textarea type="text" class="form-control input-sm" rows="1" placeholder="Enter description" id="description" name="description" value="" data-parsley-required ></textarea>
    </div>
    <div class="form-group col-sm-6">
      <label for="min_pax">Min. Pax</label>
      <input type="text" class="form-control input-sm" placeholder="Enter min pax" id="min_pax" name="min_pax" value="2" data-parsley-required data-parsley-type="digits">
    </div>
    <div class="form-group col-sm-6" align="center">
      <label for="btn">&nbsp;<br>
        <br>
      </label>
      <button class="btn btn-primary" onclick="savePkgPopup();" type="button">Save</button>
      <button class="pkg_close btn btn-default">Close</button>
    </div>
  </form>
</div>
<?php if($_REQUEST['id']!=''){ ?>
<script>
window.onload = function() {rateMasterFunction(); }

</script>
<?php } ?>
<?php include_once("includes/footer.php")?>
