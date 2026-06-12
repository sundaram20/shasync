<?php include_once("../config/auto_loader.php");

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0; 
//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			/*$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `field_value` = '".addslashes(trim($_POST['field_value']))."' and `table_name` = 'unit' ";

			echo $sql;*

		    $db->query($sql);
			$numRows= $db->num_rows();
			if($numRows == '0'){  */

			//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
			$addSql = "   	INSERT INTO `".TBL_ATTRIBUTES."` SET

							`table_name` = 'unit',
							`field_name` = 'unit_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Unit details has been added sucessfully.';
				header("location:manageUnits.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Unit details has not been saved. Please make corrections below.';
			}
		/*	}else{
				$err++;
				$_SESSION['errorMsg'] = 'Unit Master Name Already Exist.';
			} */
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			//checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'update');
			 $editSql = "   	UPDATE `".TBL_ATTRIBUTES."` SET 
							`table_name` = 'unit',
							`field_name` = 'unit_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageUnits.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE 'id' = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Unit details has not been saved. Please make corrections.';
	}
}

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."`
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
        Unit Manager
        <small>Manage Units</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Units</li>
      </ol>
    </section>
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
	
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
				
              <div class="box-body">
                
				 <div class="form-group">
                  <label for="name">Unit Name<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-underline"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Unit Name" id="field_value" name="field_value" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  data-parsley-required>
				<?php echo $err_unit_name;?>
					</div>
                </div>
				
				
				 <div class="form-group">
                  <label for="name">Description<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Description" id="field_description" name="field_description" value="<?php if($_POST) echo $_POST['field_field_description'];else echo stripslashes($row->field_description);?>" data-parsley-required>
				
					</div>
					<?php echo $err_unit_field_description;?>
                </div> 
           		<?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>                				
				<div class="form-group">
                  <label for="status">Status</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" name="status"/> Inactive
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
                  <label for="last_modified_by">Created By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageUnits.php"); '>
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


