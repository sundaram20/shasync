<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_GROUP,'view');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}else if($db->num_rows2(selectSql(TBL_GROUP,"WHERE `id_group` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes($_POST['name'])."'",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Company Group name all-ready exists in our database.</font>';
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_GROUP,'add');
			$addSql = "   	INSERT INTO `".TBL_GROUP."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`reduction` = '".addslashes($_POST['reduction'])."',
							`price_display_method` = '".addslashes($_POST['price_display_method'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Company Group details has been added sucessfully.';
				header("location:manageCompanyGroups.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Company Group has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_GROUP,'update');
			$editSql = "   	UPDATE `".TBL_GROUP."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`reduction` = '".addslashes($_POST['reduction'])."',
							`price_display_method` = '".addslashes($_POST['price_display_method'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id_group` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Company Group '.selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageCompanyGroups.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Company Group '.selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Company Group details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_GROUP."`
								WHERE `id_group` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
       Company Manager
        <small>Manage Company Groups</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Company Groups</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Company Groups</h3>
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
              <div class="box-body">
                
				 <div class="form-group">
                  <label for="name">Hotel Company Groups<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter Company Groups Title" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
				<?php echo $err_name;?>
                </div>
				
				<?php /*?>
				  <div class="form-group">
                  <label for="reduction">Reduction</label>
                  <input type="text" class="form-control" placeholder="Enter reduction amount (in digits)" id="reduction" name="reduction" value="<?php if($_POST) echo $_POST['reduction'];else echo stripslashes($row->reduction);?>" data-parsley-pattern="^[0-9]*\.[0-9]{2}$">
				<?php echo $err_reduction;?>
                </div>
				
				 <div class="form-group">
                  <label for="price_display_method">Price Display Method</label>
                  <select class="form-control" name="price_display_method" id="price_display_method">
				  	<option value="0" <?php if($_POST['price_display_method'] == '0') echo 'selected="selected"';elseif($row->price_display_method=='0')echo 'selected="selected"'?>>Tax Excluded</option>
					<option value="1" <?php if($_POST['price_display_method'] == '1') echo 'selected="selected"';elseif($row->price_display_method=='1')echo 'selected="selected"'?>>Tax Included</option>
				  </select>
				<?php echo $err_reduction;?>
                </div><?php */?>
				
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
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageCompanyGroups.php"); '>
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


