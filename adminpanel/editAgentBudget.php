<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_AGENT_BUDGET,'add');
/////////////////////////////////////////////////////////////////////////////////////
// ----------cate---------

if($_REQUEST['hotelId'] !=''){
	$disabled = 'disabled="disabled"';
  $sea = addslashes(encryptor('decrypt',$_REQUEST['season_id']));

}						

$unitUser = selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" '); 

if($_SESSION['userLevel'] !=1){
  $perSql="SELECT * FROM `fs_user_levels` WHERE id=".$_SESSION['userLevel']." AND id_shop=".$_SESSION['shop']." ";
  $resPer = mysqli_query($connNew,$perSql);

  if($resPer){
      $perData  = mysqli_fetch_object($resPer);
      if($perData->calendar_user_list_approved == 0){
       $UserRestriction =" AND id='".$_SESSION['userId']."'"; 
      }
  }
}

if($_SESSION['teamMembers'] !=""){
  $teamMembers = "AND id IN (".$_SESSION['teamMembers'].")";
}
else{
  $teamMembers ="";
}

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Budget Manager <small>Budget Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Budget Master</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="nav-tabs-custom">
        
 <style>
 table {
    border-collapse: collapse;
}
th, td {
    padding:3px !important;
}
 
 
 </style>

<?php

?>
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit : '?> Budget <?php echo 'for : '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['hotelId']))."'");  ?> </h3>
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
            <input type='hidden' value='<?php echo ($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" id="Save" ><div class="box-header">
			  <div class="row">
			  
            
                  
				 <div class="form-group col-sm-3">
                  <label for="seasonId">Season<font color="#FF0000">*</font></label>
                  <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" data-parsley-errors-container="#seasonError" onchange="getbudgetyear(this.value); agentbudgetMasterFunction();" data-parsley-required  >
											  <option value="">Select Season</option>';

											  if($_REQUEST['hotelId'] !='' && $_REQUEST['season_id'] !=''){
                        $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  ",' ORDER BY `name`');
                         
                        }
                        else
                          $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');

											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $sea){
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
                    <input type="text" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError" <?php echo $disabled; ?> readonly>
                    <input type="hidden" class="form-control " id="start_date" name="start_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->start_date));}else{ echo date("d-m-Y");}  ?>" data-parsley-required data-parsley-errors-container="#start_dateError"  readonly="true">
                  </div>
                  <!-- /.input group -->
                  <span id="start_dateError"><?php echo $err_start_date;?></span> </div>
                <div class="form-group col-sm-2">
                  <label for="end_date">End Date </label>
                  <div class="input-group">
                    <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                    <input type="text" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"  <?php echo $disabled; ?> readonly>
                    <input type="hidden" class="form-control " id="end_date" name="end_date" value="<?php if($row->start_date){echo date('d-m-Y',strtotime($row->end_date));}else{ echo date('d-m-Y',strtotime("+1 days"));} ?>" data-parsley-required data-parsley-errors-container="#end_dateError"   readonly="true">
                  </div>
                  <!-- /.input group -->
                  <span id="end_dateError"><?php echo $err_end_date;?></span> </div> 
			  </div>
              <div class="row">
                
					<div class="form-group col-sm-3">
					  <label for="hotelId">Users <font color="#FF0000">*</font></label>
					  <?php $hotelDropDown = '<select  class="form-control select2" name="hotelId" id="hotelId" data-parsley-errors-container="#hotelError" onchange="getRoom(this.value,1); agentbudgetMasterFunction(); " data-parsley-required>
												  <option value="">Select User</option>';
												  
												  if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and id_shop='".addslashes($_SESSION['shop'])."' ".$teamMembers." ".$UserRestriction." ",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_USERS," where status='1' AND `sales_status_active` = '1' and  id_shop='".addslashes($_SESSION['shop'])."' ".$teamMembers." ".$UserRestriction." ",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $row->hotelId){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$hotelDropDown .= '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $hotelDropDown .= '</select>';
												  ?>
					  <span id="hotelError"><?php echo $err_hotel;?></span> </div>
					  <?php if($unitUser==2){ 
                $hotSql = "SELECT id,CONCAT(name,', ',city) AS name FROM ".TBL_HOTELS." WHERE id IN (".$_SESSION['hotel_access'].")";
                $hotRes = mysqli_query($connNew,$hotSql);
              ?>
					  <div class="col-md-4">
              <label>Select Hotel</label>

              <select onChange="agentbudgetMasterFunction();" name="id_hotel" id="id_hotel" class="select2 form-control" data-parsley-required>
                <option value="">Select Hotel</option>
                <?php
                while($hotRow = mysqli_fetch_object($hotRes)){

                  if(encryptor('decrypt',$_REQUEST['id_hotel'])==$hotRow->id)
                    $selected="selected='selected'";
                  else
                    $selected="";

                  echo "<option ".$selected." value='".$hotRow->id."'>".$hotRow->name."</option>";
                }
                ?>
              </select>
            </div>
          <?php } ?>
				  
				<div class="clearfix"></div>	
            
				 
				
				
                
				
				
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
          
              
              
              
                            <br/>
            <div class="box-footer ">
              
              <input type='button' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" onclick="submitAgentBudgetForm();"  >
              &nbsp;&nbsp;&nbsp;&nbsp;
              <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageAgentBudget.php?page=<?php echo $_GET['page']; ?>"); '>
              <span style="color:red;" id="loaderAni"></span>
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
      <input type="text" class="form-control input-sm" placeholder="Enter meal price" id="meal" name="meal" value="0" data-parsley-required data-parsley-type="digits" readonly>
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
	




<!--create new pkg row popup  for add --- currently hidden -->
	





<?php if($_REQUEST['id']!=''){ ?>
<script>
window.onload = function() {agentbudgetMasterFunction(); }


</script>
<?php } ?>
<?php include_once("includes/footer.php")?>
<script type="text/javascript">
  $(document).ajaxStart(function(){
    $("#loaderAni").html("Uplaoding wait...");
  });

  $(document).ajaxComplete(function(){
    $("#loaderAni").html("");
  });
</script>