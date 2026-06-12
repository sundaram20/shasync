<?php 

include_once("../config/auto_loader.php");

//checkUserLevelPermission($_SESSION['userLevel'],INVOICE,'view');


unset($_SESSION['followup_hotel_id']); 
unset($_SESSION['followup_description']); 
unset($_SESSION['followup_date']); 
unset($_SESSION['followupCode']); 
unset($_SESSION['followupstatus']); 
unset($_SESSION['assign_user_id']); 
unset($_SESSION['feedback_hotel_id']); 
unset($_SESSION['feedback_description']); 
unset($_SESSION['feedback_date']); 
unset($_SESSION['assign_followup_user_id']);
unset($_SESSION['username']); 
unset($_SESSION['user_created_date']);


$followupInvoiceType='6';


if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	 $sql = "  SELECT * FROM `call`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}
	if($row->id_user != $_SESSION['userId']){
		$disabled ="disabled='disabled'";
	}
	
}	
	
	//debugData($row);						





include_once("includes/header.php");
include_once("includes/left.php");

?>
<style>
.parsley-required {
	float: left;
}
</style>

<div class="content-wrapper"> 
  
  <!-- Content Header (Page header) -->
  
  <section class="content-header">
    <h1> Call </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Call</li>
    </ol>
  </section>
  
  <!-- Main content -->
  
  <section class="content">
    <div class="row">
    
    <!-- left column -->
    
    <div class="col-md-12">
    
    <!-- general form elements -->
    
    <div class="box box-primary">
  
   
      <?php //print_r($_SESSION);?>
      <!-- /.box-header --> 
      
      <!-- form start jump-->
      
      <form name="form1" id="form1" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
        <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
        <div class="form-group has-error">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-body">
          <div class="row">
              <div class="form-group col-md-3">             
                 <label for="name">Name</label>
                 <input type="text" class="form-control " placeholder="Enter Contact Person " id="name" name="name" value="<?php echo  $row->name; ?>"  data-parsley-required>
                <?php echo $err_name;?>
			 </div>
              
              
              <div class="form-group col-md-3">           
                <label for="mobile">Mobile</label>
                <input type="text" class="form-control " placeholder="Enter Contact Email " id="mobile" name="mobile" value="<?php echo  $row->mobile; ?>"  data-parsley-required>
                <?php echo $err_mobile;?> 
			</div>
		  </div> 
		  <!--row end-->
		  </div> 
		  <!--box body ends-->
		  <div class="box-footer">
          <input type='hidden' id="Save" value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save">
          <input type='button' <?php echo $NextFollowUpDisable; ?> value='<?=($_REQUEST['eId']==''?'Send':'Edit')?>' class="btn btn-primary" name="Save" onClick="SaveSalesReport();">
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("invoices.php"); '>
          &nbsp; <span id="ajaxMsg" style="display:none;color: red">Please Wait...</span> </div>
      </form>	
            
        
          </div>
