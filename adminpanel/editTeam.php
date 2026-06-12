<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_TEAM,'view');

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/



//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;	
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}else if($db->num_rows2(selectSql(TBL_TEAM,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes($_POST['name'])."'",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Team all-ready exists in our database.</font>';
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_TEAM,'add');
			$addSql = "   	INSERT INTO `".TBL_TEAM."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_group` = '".addslashes($_POST['id_group'])."',
							
							`id_shop_group` = '1',
							`id_user_level_1`='".addslashes($_POST['id_level_1'])."',
							`id_user_level_2`='".addslashes($_POST['id_level_2'])."',
							`id_user_level_3`='".addslashes($_POST['id_level_3'])."',
							`id_user_level_4`='".addslashes($_POST['id_level_4'])."',
							`id_user_level_5`='".addslashes($_POST['id_level_5'])."',
							`ids_user_dsr_reporting`='".implode(',',$_POST['ids_dsr'])."',
							`ids_user_monthly_reporting`='".implode(',',$_POST['ids_monthly'])."' "
							;
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
							
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'Team details has been added sucessfully.';
				header("location:manageTeam.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Team has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_TEAM,'update');
			$editSql = "   	UPDATE `".TBL_TEAM."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_group` = '".addslashes($_POST['id_group'])."',
							`id_shop_group` = '1',
							`id_user_level_1`='".addslashes($_POST['id_level_1'])."',
							`id_user_level_2`='".addslashes($_POST['id_level_2'])."',
							`id_user_level_3`='".addslashes($_POST['id_level_3'])."',
							`id_user_level_4`='".addslashes($_POST['id_level_4'])."',
							`id_user_level_5`='".addslashes($_POST['id_level_5'])."',
							`ids_user_dsr_reporting`='".implode(',',$_POST['ids_dsr'])."',
							`ids_user_monthly_reporting`='".implode(',',$_POST['ids_monthly'])."' " 

							;
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Closing Type '.selectColumn(TBL_TEAM,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageTeam.php?&page=".$_REQUEST['page']);
				
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Team '.selectColumn(TBL_TEAM,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Team details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = " SELECT * FROM `".TBL_TEAM."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
        User Master
        <small>Team Master</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Team Master</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Team </h3>
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
              		
	                <div class="form-group  ">
	                  <label for="name">Team Name<font color="#FF0000">*</font></label>
	                  <input type="text" class="form-control" placeholder="Enter Team Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
					<?php echo $err_name;?>
                	</div>
                	
                	
                	
                	<div class="form-group"> 

        <label for="userlevelId"> 

        Group<font color="#FF0000">*</font> 

        </label> 

        <?php $categoryDropDown = '<select class="form-control select2" name="id_group" id="id_group" data-parsley-errors-container="#id_groupError" data-parsley-required>
		  	<option value="">Select Group</option>
		  	';
			  $resUserLevel = selectSql(TBL_GROUP_MASTER," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
			  
			if($db->num_rows2($resUserLevel)){
			    
			  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
					if($row->id_group  == $resultUserLevel->id){
					$selected = 'selected="selected"';
					}elseif($row->id_group == $resultUserLevel->id){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
					$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
				}
			}
			    if($row->id_group==0){
			        $selected = 'selected="selected"';
			    }
			    $categoryDropDown .= '<option '.$selected.' value="0">Not Applicable</option>';
			    
			
			 	echo $categoryDropDown .= '</select>';
		  	?>

        <?php echo $err_id_group;?>

        </div>
                <div class="box box-warning"> 
                	<h4>Assign User To Each Levels : </h4>
                	
	                <!-- user levels start-->
	                <div class="form-group"> 
	                	<div class="col-md-12">
		                	<label for="id_level_1"> 
							    Level 1 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_level_1" required="required">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_level_1'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_user_level_1 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
	        			</div>

	        			<div class="col-md-12">
		                	<label for="id_level_2"> 
							    Level 2 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_level_2">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_level_2'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_user_level_2 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
	        			</div>

	        			<div class="col-md-12">
		                	<label for="id_level_3"> 
							    Level 3 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_level_3">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_level_3'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_user_level_3 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
	        			</div>
	        			<hr style="color: #252525!important;width: 80%;margin-top: 5px;">
	        			<div class="col-md-12">
		                	<label for="id_level_4"> 
							    Level 4 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_level_4">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_level_4'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_user_level_4 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
	        			</div>

	        			<div class="col-md-12">
		                	<label for="id_level_5"> 
							    Level 5 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_level_5">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_level_5'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_user_level_5 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
	        			</div>
	        		</div>	
	        	</div>
	        	<hr style="color: #252525!important;width: 80%;margin-top: 2px;">

	                <!-- user level end -->

	                <div class="row ">
	            		<div class="col-md-10 box box-danger" style="width: 96%;margin-left: 2%;">
	            			<h4>Reporting Section : </h4>
	            			<div class="col-md-4">
		                	<label for="ids_dsr"> 
							    Dsr Reporting<font color="#FF0000">*</font> 
							</label> 

		        			<?php  $sqlUserSql = "SELECT * FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."' AND status='1'  ";

		        			
						 $sqlUserActions = mysqli_query($connNew,$sqlUserSql);  

				   ?>
                  
				  <select required="required" class="form-control select2" name="ids_dsr[]" multiple="multiple">				  
                  <?php 
									
					$iCounterActions = 0;
					while($resUserActions = mysqli_fetch_object($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_TEAM."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_user_dsr_reporting ) AND id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";

						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST['ids_dsr']){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
					
                    
                </div>	

                <div class="col-md-4">
		                	<label for="ids_dsr"> 
							    Monthly Reporting<font color="#FF0000">*</font> 
							</label> 

		        			<?php  $sqlUserSql = "SELECT * FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."' AND status='1'  ";

		        			
						 $sqlUserActions = mysqli_query($connNew,$sqlUserSql);  

				   ?>
                  
				  <select required="required" class="form-control select2" name="ids_monthly[]" multiple="multiple">				  
                  <?php 
									
					$iCounterActions = 0;
					while($resUserActions = mysqli_fetch_object($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_TEAM."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_user_monthly_reporting ) AND id='".addslashes(encryptor('decrypt',$_REQUEST['eId']))."' ";

						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST['ids_dsr']){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
					
                    
                </div>	
	        			<hr style="color: #252525!important;width: 100%;margin-top: 2px;">
	            		</div>

            		</div>

				<div class="col-md-12">				
					<div class="form-group">
	                  <label for="status">Status</label>
	                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
					 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
					 <?php echo $err_status;?>
	                </div>
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
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageTeam.php"); '>
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


