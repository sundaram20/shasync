<?php include_once("../config/auto_loader.php");


checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_POINTS,'view');

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_POINTS,'add');
			$addSql = "   	INSERT INTO `".TBL_RATE_POINTS."` SET 
							`parent_id` = '".addslashes($_POST['parent_id'])."',
							`title` = '".addslashes($_POST['title'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',							
							`description` = '".addslashes($_REQUEST['description'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Rate Points details has been added sucessfully.';
				header("location:manageRatePoints.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Rate Points has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE_POINTS,'update');
			 $editSql = "   	UPDATE `".TBL_RATE_POINTS."` SET 
							
							`title` = '".addslashes($_POST['title'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',		
							`description` = '".addslashes($_REQUEST['description'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['eId']))."'";
									
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Rate Points updated sucessfully.';
				header("location:manageRatePoints.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Rate Points details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Rate Points details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_RATE_POINTS."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
        <small>Rate Points </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Rate Points </li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Rate Points</h3>
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
                        <input class="form-control" type="text" name="title" id="title" value="<?php echo stripslashes($row->title);?>"	> 
							<?php /*?>	<select class="form-control select2" name="parent_id" id="parent_id" data-parsley-errors-container="#parent_idError" >
									<option value="0">Main Category</option>
									<?php 
									$resCat = selectSql(TBL_RATE_POINTS,"where parent_id='0' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['parent_id'] == $resultCat->id){
															$selected = 'selected="selected"';
														}elseif($row->parent_id == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$parentDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->description).'</option>';
													}
												  }
												  echo $parentDropDown;
									
									 ?>
								</select><?php */?>
							  <span id="parent_idError"><?php echo $err_parent_id; ?></span>
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
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageRatePoints.php"); '>
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


