<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');
/////////////////////////////////////////////////////////////////////////////////////
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	//$sql = "SELECT * FROM `".TBL_RATE."` as a join `".TBL_RATE_DETAILS."` as b where b.`id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'  AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id ";
	$db->query($sql);
	if($db->num_rows() > 0){	
	
		$row = $db->fetch_object();
	}						
}	
	
					

$editseasonId 	=	selectColumn(TBL_TAX_CONFIGURATION_TWO,'seasonId'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");
$editid_hotel 	=	selectColumn(TBL_TAX_CONFIGURATION_TWO,'id_hotel'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'");   
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Tax Manager <small>Tax Configuration Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Tax Configuration  Master</li>
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
            <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Tax Configuration </h3>
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
            <div class="box-header">
			  <div class="row">
			  
                  
                      
                  
			  
                  
				 <div class="form-group col-sm-3">
                  <label for="seasonId">Season<font color="#FF0000">*</font></label>
                  <?php $seasonDropDown = '<select class="form-control select2" name="seasonId" id="seasonId" data-parsley-errors-container="#seasonError" onchange="getseasonDate(this.value); editTaxConfigurationTwoFunction();" data-parsley-required  '.$disabled.'>
											  <option value="">Select Season</option>';
											  $resCat = selectSql(TBL_RATE_SEASON," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($resultCat->id == $editseasonId){
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
					  <label for="hotelId">Hotel <font color="#FF0000">*</font></label>
					  <?php $hotelDropDown = '<select class="form-control select2" name="hotelId" id="hotelId" data-parsley-errors-container="#hotelError" onchange="getRoom(this.value,1); editTaxConfigurationTwoFunction(); getInclusionDetail(this.value);" data-parsley-required>
												  <option value="">Select Hotel</option>';
												  
												  if(empty($_SESSION['hotel_access'])){
													$resCat = selectSql(TBL_HOTELS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');		
												  }else{
												  $resCat = selectSql(TBL_HOTELS," where status='1' and find_in_set(id,'".$_SESSION['hotel_access']."') and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `name`');												}
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($resultCat->id == $editid_hotel){
															$selected = 'selected="selected"';
														}else if(encryptor('decrypt',$_REQUEST['hotelId'])== $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}	
														$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
													echo $hotelDropDown .= '</select>';
												  ?>
					  <span id="hotelError"><?php echo $err_hotel;?></span> </div>
					  
					  
				  
				
              
				 
				
				
				 				
				
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
             
              
              
              
                            <br/>
            <div class="box-footer ">
              <input type='hidden' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              <input type='button' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" onclick="submitTaxConfigurationTwoForm();"  >
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
window.onload = function() {editTaxConfigurationTwoFunction(); }

</script>
<?php } ?>
<?php include_once("includes/footer.php")?>
