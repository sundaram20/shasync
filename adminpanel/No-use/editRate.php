<?php include_once("includes/autoloader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');
$image_path = $UPLOAD_FILES.'/hotel_room/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_room/";
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){ header("location:editHotels.php"); }
/////////////////////////////////////////////////////////////////////////////////////
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE."`
								WHERE `id` = '".addslashes($_REQUEST['id'])."'";
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
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Hotel Manager <small>Rate Master</small> </h1>
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
          <ul class="nav nav-tabs">
            <li><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li>
            <li><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li>
            <li><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Room Types</a></li>
            <li class="active"><a href="manageRate.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Rate Master</a></li>
			<li><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Inventory</a></li>
			<li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>
          </ul>
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Rate : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($_REQUEST['eId'])."'"); ?> </a></h3>
            <a href="manageRate.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a> </div>
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
            <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="hotelId" />
            <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
            <div class="box-header">
              <div class="row">
                <div class="form-group col-sm-3">
                  <label for="seasonId">Season<font color="#FF0000">*</font></label>
                  <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" data-parsley-errors-container="#seasonError" onchange="getseasonDate(this.value); rateMasterFunction();" data-parsley-required  '.$disabled.'>
											  <option value="">Select Season</option>';
											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' ",' ORDER BY `name`');
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
                    <input type="text" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" <?php echo $disabled; ?> readonly="true">
                  </div>
                  <!-- /.input group -->
                  <span id="start_dateError"><?php echo $err_start_date;?></span> </div>
                <div class="form-group col-sm-2">
                  <label for="end_date">End Date </label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  <?php echo $disabled; ?> readonly="true">
                  </div>
                  <!-- /.input group -->
                  <span id="end_dateError"><?php echo $err_end_date;?></span> </div>
                <div class="form-group col-sm-5">
                  <label for="roomId">Room Type<font color="#FF0000">*</font></label>
                  <?php $roomDropDown = '<select class="form-control select2" multiple="multiple" data-placeholder="Please Select Room" name="roomId[]" id="roomId" onchange="rateMasterFunction();" data-parsley-required data-parsley-errors-container="#room_idError">';
											  $resRoom = executeSql("SELECT rt.name, ahr.hotel_id, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($_REQUEST['eId'])."'");
											  if(num_rows($resRoom)){
											  	while($resultRoom = $db->fetch_object2($resRoom)){
													if(in_array($resultRoom->room_id,explode(',',$row->room_ids))){
													$selected = 'selected="selected"';
													}else{
													$selected = '';
													}
													$roomDropDown .= '<option '.$selected.' value="'.$resultRoom->room_id.'">'.ucfirst($resultRoom->name).'</option>';
												}
											  }
											 	echo $roomDropDown .= '</select>';
											  ?>
                  <span id="room_idError"><?php echo $err_room_id;?></span> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-2">
                  <label for="rate_level_id">Rate Level<font color="#FF0000">*</font></label>
                  <select class="form-control input-sm" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="rateMasterFunction();" <?php echo $disabled; ?>>
                    <option value="">Select Rate Level</option>
                    <?php $resCat = selectSql(TBL_RATE_LEVEL," where status='1' ",' ORDER BY `name`');
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
                
                <?php 
					
					$inclusionDetail= json_decode($row->inclusion_detail,true);
					
					$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and type='1'"); 
				 
				 	while($rowRateInclusion = $db->fetch_object2($resRateInclusion)){
					
					if($inclusionDetail[$rowRateInclusion->id]!=''){
						$inclusionValue = $inclusionDetail[$rowRateInclusion->id];
					}else{
						$inclusionValue = 0;
					}
					
					echo ' <div class="form-group col-sm-1"> 
					  <label for="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'">'.$rowRateInclusion->name.'</label> 
					  <input type="hidden" name="inclusion_id[]" value="'.$rowRateInclusion->id.'" />
					  <input type="text" class="form-control input-sm inclusionFood" placeholder="Enter '.$rowRateInclusion->name.' Price" id="inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'" name="inclusion_detail[]" value="'.$inclusionValue.'" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);"> 
                  </div> ';
					
					}
				 
				 ?>
                <div class="form-group col-sm-1">
                  <label for="extras">Extras</label>
                  <input type="hidden" name="inclusion_id[]" value="0" />
                  <input type="hidden" name="inclusion_extra" id="inclusion_extra" value="0" />
                  <input type="text" class="form-control input-sm inclusionExtra" placeholder="Enter extras price" id="extras" name="inclusion_detail[]" value="<?php if($inclusionDetail['0']!=''){echo $inclusionDetail['0'];}else{echo '0';} ?>" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
                </div>
                <div class="form-group col-sm-3">
                  <label for="remarks">Remarks </label>
                  <textarea class="form-control input-sm" name="remarks" id="remarks" rows="1" placeholder="Enter Remarks" automcomplete="off" ><?php echo $row->remarks; ?></textarea>
                </div>
              </div>
            </div>
          </form>
          <form name="form1" id="rateUpdate"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
            <div class="box-body no-padding text-center loading" >
              <button type="button" class="btn btn-default btn-lrg ajax" title="Loading..."> <i class="fa fa-spin fa-refresh"></i>&nbsp; Loading... </button>
            </div>
            <div class="box-body box-primary" id="rateMasterDetail"> </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <input type='hidden' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              <input type='button' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" onClick="submitRateForm();"  >
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRate.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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





<!--create pkg popup for ----->
<div id="pkgPopup" class="well" style="max-width:44em;">
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
      <div class="input-group"><input type="text" class="form-control input-sm" id="pkg_discount" name="pkg_discount" value="0" data-parsley-required automcomplete="off" data-parsley-type="digits" data-parsley-maxlength="2"><span class="input-group-addon" ><i class="fa fa-percent"></i></span></div>
    </div>
	
	 <div class="form-group col-sm-6">
      <label for="pkg_extra_price">Extra Price</label>
      <input type="text" class="form-control input-sm" placeholder="Enter package pax price" id="pkg_extra_price" name="pkg_extra_price" value="0" data-parsley-required data-parsley-type="digits">
    </div>
	<div class="form-group col-sm-12" align="center">
		 
		<button class="btn btn-primary" onclick="savePkgPopupData();" type="button">Save</button>
		<button class="pkgPopup_close btn btn-default" onClick="this.form.reset();">Close</button>
	</div>
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
		 <label for="btn">&nbsp;<br><br></label>
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
