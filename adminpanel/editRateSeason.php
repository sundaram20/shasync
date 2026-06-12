<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_SEASON,'view');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}else if($db->num_rows2(selectSql(TBL_RATE_SEASON,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."'  AND `name` = '".addslashes($_POST['name'])."'",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Rate Seasons name all-ready exists in our database.</font>';
	}	
	if(strtotime($_POST['end_date']) <= strtotime($_POST['start_date'])){
	    $err++;
		$err_end_date = '<font style="color:red;font-weight:normal;" ><br>End Date can\'t be less than start date.</font>';	
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_SEASON,'add');
			$addSql = "   	INSERT INTO `".TBL_RATE_SEASON."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`season_type`='".$_REQUEST['season_type']."',
							`start_date` = '".date('Y-m-d',strtotime($_POST['start_date']))."',
							`end_date` = '".date('Y-m-d',strtotime($_POST['end_date']))."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Rate Seasons details has been added sucessfully.';
				header("location:manageRateSeason.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Rate Seasons has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_SEASON,'update');
			$editSql = "   	UPDATE `".TBL_RATE_SEASON."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`season_type`='".$_REQUEST['season_type']."',
							`start_date` = '".date('Y-m-d',strtotime($_POST['start_date']))."',
							`end_date` = '".date('Y-m-d',strtotime($_POST['end_date']))."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Rate Seasons '.selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageRateSeason.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Rate Seasons '.selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Rate Seasons details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE_SEASON."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}						
}	
							

?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Rate Manager
        <small>Manage Rate Seasons</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Rate Seasons</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Rate Seasons</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>


				<?php
					if($row->season_type==1)
						$selected_1="selected='selected'";
					elseif($row->season_type==2)
						$selected_2="selected='selected'";
					elseif($row->season_type==3)
						$selected_3="selected='selected'";
					elseif($row->season_type==4)
						$selected_4="selected='selected'";
					elseif($row->season_type==5)
						$selected_5="selected='selected'";
					else{
						$selected_1='';
						$selected_2='';
						$selected_3='';
						$selected_4='';
						$selected_5='';
					}
				?>

              <div class="box-body">
				<div class="form-group">
                  <label for="name">Seasons Type<font color="#FF0000">*</font></label>
                  <select name="season_type" required="required" class="select2 form-control">
                  	<option value="">---Select Type---</option>
                  	<option <?php echo $selected_1;?> value="1">Calendar Year</option>
                  	<option <?php echo $selected_5;?> value="5">Financial Year</option>
                  	<option <?php echo $selected_2;?> value="2">Summer</option>
                  	<option <?php echo $selected_3;?> value="3">Winter</option>
                  	<option <?php echo $selected_4;?> value="4">Custom</option>
                  </select>
                </div>	

                
				<div class="form-group">
                  <label for="name">Rate Seasons Name<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter rate season name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
				<?php echo $err_name;?>
                </div>
				
				<div class="form-group">
                  <label for="start_date">Start Date</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter start date" id="start_date" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_start_date;?>
                </div>
				<div class="form-group">
                  <label for="end_date">End Date</label>
                  <input type="text" class="form-control pickerdate" placeholder="Enter end date" id="end_date" name="end_date" value="<?php if($_POST) echo $_POST['end_date'];elseif($row->end_date) echo stripslashes(date('d-m-Y',strtotime($row->end_date))); else echo date('d-m-Y'); ?>"  data-parsley-required>
				<?php echo $err_end_date;?>
                </div>
								
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
				<?php if($row->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRateSeason.php"); '>
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


