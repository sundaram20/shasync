<?php include_once("../config/auto_loader.php");


 checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_MAIL_FORMAT,'view');

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_MAIL_FORMAT,'add');
			$addSql = "   	INSERT INTO `".TBL_RATE_MAIL_FORMAT."` SET 
							`name` = '".addslashes($_POST['title'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`type` = '".addslashes($_REQUEST['type'])."',							
							`description` = '".addslashes($_REQUEST['description'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Mail Format details has been added sucessfully.';
				header("location:manageDailyPickupMailFormat.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Mail  has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_MAIL_FORMAT,'update');
			 $editSql = "   	UPDATE `".TBL_RATE_MAIL_FORMAT."` SET 
							`name` = '".addslashes($_POST['title'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',	
							`type` = '".addslashes($_REQUEST['type'])."',	
							`description` = '".addslashes($_REQUEST['description'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['eId']))."'";
									
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Mail Updated Sucessfully.';
				header("location:manageDailyPickupMailFormat.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Mail details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Mail  details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE_MAIL_FORMAT."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
	  Manage
        <small>Mail Format</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mail Format </li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Mail Format</h3>
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
				
				
						<label for="parent_id" >Title</label>  
								
								<input class="form-control" type="text" name="title" id="title" value="<?php echo stripslashes($row->name);?>"	>
							  <span id="parent_idError"><?php echo $err_parent_id; ?></span>
							</div>
				<div class="form-group ">
					
						<label for="type" >Type</label>  
						<select class="form-control" name="type" required="required">
							<option value="">Select Type</option>
							<option <?php echo  $row->type==1?"selected='selected'":''?> value="1">Header</option>
							<option <?php echo $row->type==2?"selected='selected'":''?> value="2">Content</option>
							<option <?php echo $row->type==3?"selected='selected'":''?> value="3">Footer</option>
						</select>
					
				</div>			
				
			<script src="<?php echo $SITE_URL; ?>/adminpanel/ckeditor/ckeditor.js" ></script>	
				
				<div class="form-group">
                  <label for="description">Description</label>
  <textarea cols="75"  id="description" class="required" title="Enter Product Description" name="description" rows="10"><?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?></textarea>
  
<!--				    <textarea class="form-control" name="description" id="description"  rows="2" placeholder="Enter description" data-parsley-required automcomplete="off"><?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?></textarea>-->
                   <script type="text/javascript" language="javascript">
                    if (CKEDITOR.instances['header_text']) {
                    CKEDITOR.remove(CKEDITOR.instances['description']);
                    }
                    CKEDITOR.replace( 'description',
                    {
                        fullPage : true,
                        extraPlugins : 'docprops'
                    });
                    </script>
				<?php echo $err_description;?>
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
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("editMailFormat.php"); '>
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


