<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	

	$err = 0;
	
	
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'add');
			$addSql = "   	INSERT INTO `".TBL_USERS."` SET 
							`id_shop` = '".addslashes($_POST['id_shop'])."',
							`id_shop_group` = '1',
							`user_level` = '".addslashes($_POST['userlevelId'])."',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`username` = '".addslashes($_POST['username'])."'";
			$addSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$addSql .= "	,`hotel_access` = '".addslashes(implode(',',$_POST['hotel_access']))."'";
			$addSql .= "	,`designation` = '".trim(addslashes($_POST['designation']))."'";
			$addSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
			$addSql .= "	,`mobile` = '".addslashes($_POST['mobile'])."'";
			$addSql .= "	,`company` = '".addslashes($_POST['company'])."'";
			$addSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$addSql .= "	,`address2` = '".addslashes($_POST['address2'])."'";
			$addSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$addSql .= "	,`location` = '".addslashes($_POST['location'])."'";
			$addSql .= "	,`zip` = '".addslashes($_POST['zip'])."'";
			$addSql .= "	,`skype` = '".addslashes($_POST['skype'])."'";
			
			$addSql .= "	,`comments` = '".addslashes($_POST['comments'])."'";
			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				$_SESSION['successMsg'] = 'New User details has been added sucessfully.';
				header("location:manageUsers.php?userlevelId=".$_POST['userlevelId']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		//$dsr_num_days = date('d') - $_REQUEST['dsr_num_days'];
		$dsr_num_days = $_REQUEST['dsr_num_days'];
	//echo $dsr_stat_date	=	date("Y-04-".$Days);
		
		//print_r($_REQUEST['dsr_num_days']);
		
		//$dsr_stat_date;
		//die;
		$MultipleUserAccessSelected = implode (", ", $_REQUEST['MultipleUserAccessSelected']);
			checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'update');
			$editSql = "   	UPDATE `".TBL_USERS."` SET 
							`user_access_rateletter` = '".addslashes($MultipleUserAccessSelected)."'";
							
			
		
			$editSql .= "	
							WHERE `id` = '".addslashes($_POST['eId'])."'";
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'User details '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' has been updated sucessfully.';
				header("location:manageUsers.php?userlevelId=".$_POST['userlevelId']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'User details has not been saved.Please make corrections.';
	}
}

// ----------cate---------


if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sqlUserDetail = "  SELECT * FROM `".TBL_USERS."`
						WHERE `id` = '".addslashes($_REQUEST['eId'])."'";
	$db->query($sqlUserDetail);
	if($db->num_rows() > 0){
		$rowUserDetail = $db->fetch_object();
		if($rowUserDetail->user_access_rateletter!=''){
			
			//$List	=	array($rowUserDetail->user_access_rateletter);
			
			
			$myArray = explode(',', $rowUserDetail->user_access_rateletter);



			$SelectUserAccess	=	"AND id NOT IN (".$rowUserDetail->user_access_rateletter.")";
			$SelectUser	=	"AND id IN (".$rowUserDetail->user_access_rateletter.")";
		}
	}						
}	

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>

  
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.css">
  
  <link rel="stylesheet" type="text/css" href="../src/bootstrap-duallistbox.css">
  
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>-->
  
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  -->
  <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
  
  <script src="../src/jquery.bootstrap-duallistbox.js"></script>
  
  
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> User Access Permissions <small> Manage User Permissions</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="manageUsers.php"> User Permissions</a></li>
      <li class="active">Manage Users Permissions</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Users Permissions</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form name="form1"  method="post" enctype="multipart/form-data" role="form">
      <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
      <div class="form-group has-error">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <div class="box-body">
      <div class="form-group">
        <label for="name">Name<font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter your name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>" readonly>
        <?php echo $err_name;?> </div> 
        
        <div class="form-group"> 
       
           
 <?php  
 $categoryDropDown = '<select class="form-control" multiple="multiple" name="MultipleUserAccessSelected[]" title="MultipleUserAccessSelected[]" style="height:220px;">
 											  								';
		 $resUserLevel = selectSql(TBL_STATE," WHERE `status` = '1'  AND `id_country` ='110' ",' ORDER BY `name`');
					  if($db->num_rows2($resUserLevel)){
						while($resultUserLevel = $db->fetch_object2($resUserLevel)){
							if(!empty($myArray)){
										if (in_array($resultUserLevel->id_state, $myArray)){																
											$selected = 'selected="selected"';																	
										}else{
											$selected = '';																	
										}
							}else{
								$selected = '';
								}
							$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id_state.'">'.ucfirst($resultUserLevel->name).'</option>';							
						}
					  }
						echo $categoryDropDown .= '</select>';
				 ?>
                                              
  <br>
  <br>
<script>

$('document').ready(function(){
	
	$('select[name="MultipleUserAccessSelected[]"]').bootstrapDualListbox("<option value=123' >hitesh aloney</option>");
});

  var demo1 = $('select[name="MultipleUserAccessSelected[]"]').bootstrapDualListbox(); 
 
  $("#demoform").submit(function() {
    alert($('[name="MultipleUserAccessSelected[]"]').val());
    return false;
  });
</script>
        
    
        
        
        
      <div class="form-group">
        <label for="status">Status</label>
        <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserDetail->status == 1)echo "checked";}?> value="1" name="status"/>
        Active
        <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserDetail->status == 0)echo "checked";}?> value="0" name="status"/>
        Inactive <?php echo $err_status;?> </div>
      <?php if($rowUserDetail->date_created){?>
      <div class="form-group">
        <label for="date_created">Date Created</label>
        <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserDetail->date_created));?>">
      </div>
      <div class="form-group">
        <label for="last_modified">Last Updated</label>
        <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserDetail->last_modified));?>">
      </div>
      <div class="form-group">
        <label for="last_modified_by">Last Updated By</label>
        <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserDetail->last_modified_by."'",''));?>
        <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">
      </div>
      <?php } ?>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type='button' value='Cancel' class="btn btn-default" onclick='window.location.replace("manageUsers.php"); '>
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
<?php include_once("includes/footer.php")?>

