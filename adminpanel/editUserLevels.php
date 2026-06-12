<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'view');

$image_path = $UPLOAD_FILES.'/users/';

$image_display_path = $UPLOAD_FILES_PATH ."/users/";

//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){

	$err = 0;

	if(empty($_POST['name'])){

		$err++;

		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter user level title.</font>';

	}else if($db->num_rows2(selectSql(TBL_USER_LEVELS,"WHERE `id` NOT IN('".addslashes($_REQUEST[eId])."') AND `name` = '".addslashes($_POST['name'])."'",''))){

		$err++;

		$err_name = '<font style="color:red;font-weight:normal;" ><br>User Level name all-ready exists in our database.</font>';

	}

	

	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'add');

			$addSql = "   	INSERT INTO `".TBL_USER_LEVELS."` SET 

							`name` = '".addslashes($_POST['name'])."'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							,`id_shop` = '".$_SESSION['shop']."'

							,`conveyance_approved` = '".$_POST['conveyance_approved']."'
							,`teamdataaccess_approved` = '".$_POST['teamdataaccess_approved']."'
							,lead_summary_approval='".$_POST['lead_summary_approval']."'
							,`master_menu_approval`='".$_POST['master_menu_approval']."'
							,`customer_access_approval` = '".$_POST['customer_access_approval']."'
							,`calendar_user_list_approved` = '".$_POST['calendar_user_list_approved']."'

							,`rate_approved` = '".$_POST['rate_approved']."'

							,`status` = '".addslashes($_POST['status'])."'";



			if(executeSql($addSql)){

				unset($_POST);

				$_SESSION['successMsg'] = 'New User Level details has been added sucessfully.';

				header("location:manageUserLevels.php");

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'User Level details has not been saved. Please make corrections below.';

			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update

			checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'update');

			$editSql = "   	UPDATE `".TBL_USER_LEVELS."` SET 

							`name` = '".addslashes($_POST['name'])."'";

			$editSql .= "	,`last_modified` = '".currenDateTime()."'

							,`status` = '".addslashes($_POST['status'])."'

							,`conveyance_approved` = '".$_POST['conveyance_approved']."'
                            ,`teamdataaccess_approved` = '".$_POST['teamdataaccess_approved']."'
							,`calendar_user_list_approved` = '".$_POST['calendar_user_list_approved']."'
							,lead_summary_approval='".$_POST['lead_summary_approval']."'
							,`master_menu_approval`='".$_POST['master_menu_approval']."'
							,`customer_access_approval` = '".$_POST['customer_access_approval']."'
							,`rate_approved` = '".$_POST['rate_approved']."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							WHERE `id` = '".addslashes($_POST['eId'])."'";

								

			if(executeSql($editSql)){

				$_SESSION['successMsg'] = 'User Level '.selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has been updated sucessfully.';

				header("location:manageUserLevels.php");

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'User Level '.selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';

			}

		}

	}else{//Error

		$err++;

		$_SESSION['errorMsg'] = 'User Level details has not been saved.Please make corrections.';

	}

}

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sqlUserLevelDetail = "  SELECT * FROM `".TBL_USER_LEVELS."`

								WHERE `id` = '".addslashes($_REQUEST['eId'])."'";

	$db->query($sqlUserLevelDetail);

	if($db->num_rows() > 0){

		$rowUserLevelDetail = $db->fetch_object();

	}						

}	

							



?>

<?php include_once("includes/header.php")?>

<?php include_once("includes/left.php")?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

       User Levels Manager

        <small>Manage User Levels</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="manageUsers.php">User Manager</a></li>

        <li class="active">Manage Users</li>

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

              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> User Levels</h3>

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

                  <label for="name">User Level Name<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter your user level name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserLevelDetail->name);?>">

				<?php echo $err_name;?>

                </div>



                <!--special permission section-->

                <br>

                <label >Special Permissions : </label><br>


                <div class="form-group">

                  <label for="name">Team Data Access<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['teamdataaccess_approved'] == '1'){echo "checked";}else{if($rowUserLevelDetail->teamdataaccess_approved == 1)echo "checked";}?> value="1" name="teamdataaccess_approved"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['teamdataaccess_approved'] == '0'){echo "checked";}else{if($rowUserLevelDetail->teamdataaccess_approved == 0)echo "checked";}?> value="0" name="teamdataaccess_approved"/> Not Allowed

				<?php echo $err_name;?>

                </div>

                <div class="form-group">

                  <label for="name">Conveyance Apporval<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['conveyance_approved'] == '1'){echo "checked";}else{if($rowUserLevelDetail->conveyance_approved == 1)echo "checked";}?> value="1" name="conveyance_approved"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['conveyance_approved'] == '0'){echo "checked";}else{if($rowUserLevelDetail->conveyance_approved == 0)echo "checked";}?> value="0" name="conveyance_approved"/> Not Allowed

				<?php echo $err_name;?>

                </div>



                <div class="form-group">

                  <label for="name">Rate Apporval<font color="#FF0000">*</font></label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['rate_approved'] == '1'){echo "checked";}else{if($rowUserLevelDetail->rate_approved == 1)echo "checked";}?> value="1" name="rate_approved"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['rate_approved'] == '0'){echo "checked";}else{if($rowUserLevelDetail->rate_approved == 0)echo "checked";}?> value="0" name="rate_approved"/> Not Allowed

				<?php echo $err_name;?>

                </div>



				<div class="form-group">

                  <label for="name">Calendar User List Apporval<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['calendar_user_list_approved'] == '1'){echo "checked";}else{if($rowUserLevelDetail->calendar_user_list_approved == 1)echo "checked";}?> value="1" name="calendar_user_list_approved"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['calendar_user_list_approved'] == '0'){echo "checked";}else{if($rowUserLevelDetail->calendar_user_list_approved == 0)echo "checked";}?> value="0" name="calendar_user_list_approved"/> Not Allowed

				<?php echo $err_name;?>

                </div>


                <div class="form-group">

                  <label for="name">Display All Leads<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['lead_summary_approval'] == '1'){echo "checked";}else{if($rowUserLevelDetail->lead_summary_approval == 1)echo "checked";}?> value="1" name="lead_summary_approval"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['lead_summary_approval'] == '0'){echo "checked";}else{if($rowUserLevelDetail->lead_summary_approval == 0)echo "checked";}?> value="0" name="lead_summary_approval"/> Not Allowed

				<?php echo $err_name;?>

                </div>
				<div class="form-group">

                  <label for="name">Master Menu access<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['master_menu_approval'] == '1'){echo "checked";}else{if($rowUserLevelDetail->master_menu_approval == 1)echo "checked";}?> value="1" name="master_menu_approval"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['master_menu_approval'] == '0'){echo "checked";}else{if($rowUserLevelDetail->master_menu_approval == 0)echo "checked";}?> value="0" name="master_menu_approval"/> Not Allowed

				<?php echo $err_name;?>

                </div>
				  <!--New option added by afsal-->
				  <!--<div class="form-group">

                  <label for="name">Customer Management Access<font color="#FF0000">*</font></label>&nbsp&nbsp

                  <input type="radio" class="flat-red" <?php if($_POST['customer_access_approval'] == '1'){echo "checked";}else{if($rowUserLevelDetail->customer_access_approval == 1)echo "checked";}?> value="1" name="customer_access_approval"/> Allowed

				 <input type="radio" class="flat-red" <?php if($_POST['customer_access_approval'] == '0'){echo "checked";}else{if($rowUserLevelDetail->customer_access_approval == 0)echo "checked";}?> value="0" name="customer_access_approval"/> Not Allowed

				<?php echo $err_name;?>

                </div>-->
				<!--end-->

				<div class="form-group">

                  <label for="status">Status</label>

                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserLevelDetail->status == 1)echo "checked";}?> value="1" name="status"/> Active

				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserLevelDetail->status == 0)echo "checked";}?> value="0" name="status"/> Inactive

				 <?php echo $err_status;?>

                </div>

				

				<?php if($rowUserLevelDetail->date_created){?>

				  

				<div class="form-group">

                  <label for="date_created">Date Created</label>

                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserLevelDetail->date_created));?>">				

                </div> 

				

				<div class="form-group">

                  <label for="last_modified">Last Updated</label>

                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserLevelDetail->last_modified));?>">				

                </div> 

				

				<div class="form-group">

                  <label for="last_modified_by">Last Updated By</label>

				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserLevelDetail->last_modified_by."'",''));?>

                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				

                </div>  

				  

				  <?php } ?>            

              </div>

              <!-- /.box-body -->	

			 <div class="box-footer">                                       

				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >

				&nbsp;&nbsp;&nbsp;&nbsp;

			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageUserLevels.php"); '>

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





