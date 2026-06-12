<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_STATE,'view');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$image_path = $UPLOAD_FILES.'/users/';

$image_display_path = $UPLOAD_FILES_PATH ."/users/";

//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){

	$err = 0;	

	if(empty($_POST['name'])){

		$err++;

		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';

	}else if($db->num_rows2(selectSql(TBL_STATE,"WHERE `id_state` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."')  AND `name` = '".addslashes($_POST['name'])."'",''))){

		$err++;

		$err_name = '<font style="color:red;font-weight:normal;" ><br>Zone name all-ready exists in our database.</font>';

	}

	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			
			checkUserLevelPermission($_SESSION['userLevel'],TBL_STATE,'add');

			$addSql = "   	INSERT INTO `".TBL_STATE."` SET 
							`name` = '".addslashes($_POST['name'])."',
							`id_country` = '110'";
			$addSql .= "	
							,`status` = '".addslashes($_POST['status'])."'";

			if(mysqli_query($conn,$addSql)){

				$insertedStateId = mysqli_insert_id($conn);

				$editSql = "   	INSERT INTO  `".MST_TBL_STATE_ZONE."` SET 
								`id_state` = '".$insertedStateId."',
								`id_zone` = '".$_POST['zone']."' 
								,`date_created` = '".date('Y-m-d')."'
								,`last_modified` = '".date('Y-m-d H:i:s')."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`id_shop` = '".$_SESSION['shop']."' ";

				$editSql .= "	,`status` = '".addslashes($_POST['status'])."'";

				if(mysqli_query($conn,$editSql)){
					unset($_POST);

					$_SESSION['successMsg'] = 'New State details has been added sucessfully.';

					header("location:manageState.php");

					exit;
				}
				else{
					unset($_POST);

					$_SESSION['errorMsg'] = 'Error While updating';

					header("location:manageState.php");

					exit;
				}

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'State has not been saved. Please make corrections below.';

			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update

			checkUserLevelPermission($_SESSION['userLevel'],MST_TBL_STATE_ZONE,'update');

			$chkForData = "SELECT * FROM ".MST_TBL_STATE_ZONE." WHERE id_state='".$_REQUEST['id_state']."' AND id_shop='".$_SESSION['shop']."' ";
			
			$resChk = mysqli_query($conn,$chkForData);

			if(mysqli_num_rows($resChk)>0){

				$editSql = "   	UPDATE `".MST_TBL_STATE_ZONE."` SET 
								`id_zone` = '".$_POST['zone']."' ";

				$editSql .= "	,`status` = '".addslashes($_POST['status'])."'
									,`last_modified` = '".date('Y-m-d H:i:s')."'
									,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
									

								WHERE  id_shop='".$_SESSION['shop']."' AND `id_state` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";

			}
			else{
				$editSql = "   	INSERT INTO  `".MST_TBL_STATE_ZONE."` SET 
								`id_state` = '".addslashes($_POST['id_state'])."',
								`id_zone` = '".$_POST['zone']."' 
								,`date_created` = '".date('Y-m-d')."'
								,`last_modified` = '".date('Y-m-d H:i:s')."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`id_shop` = '".$_SESSION['shop']."' ";

				$editSql .= "	,`status` = '".addslashes($_POST['status'])."'";
			}
			
							

			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';

				header("location:manageState.php?&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';

			}

		}
	}else{//Error

		$err++;

		$_SESSION['errorMsg'] = 'State details has not been saved.Please make corrections.';

	}	
}

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = " SELECT * FROM `".TBL_STATE."` WHERE `id_state` = '".encryptor('decrypt',$_REQUEST['eId'])."'  ";

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

        Attributes Manager

        <small>State Master</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">State Master</li>

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

              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> State</h3>

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

                  <label for="name">State Name<font color="#FF0000">*</font></label>

                  <input type="hidden" class="form-control" placeholder="Enter State name" id="id_state" name="id_state" value="<?php if($_POST) echo $_POST['id_state'];else echo stripslashes($row->id_state);?>" data-parsley-required>
                  
                  <input type="text" class="form-control" placeholder="Enter State name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>

              	 <?php echo $err_name;?>

                </div>

                <div class="form-group">
                  <label for="state">Zone<font style="color:red;">*</font></label>
                  <?php 
					$sqlUserSql = "SELECT * FROM ".TBL_ZONAL." WHERE status='1'  ";
					$sqlUserActions = mysqli_query($conn,$sqlUserSql);
					$iCounterActions = 0;?>
				  <select required="required" class="form-control select2" name="zone" >
				  <option value="">Select Zone</option>				  
                  
					<?php
					while($resUserActions = mysqli_fetch_object($sqlUserActions)){
						$chkSql = "SELECT * FROM `".MST_TBL_STATE_ZONE."` WHERE id_zone = '".$resUserActions->id."' AND id_state = '".encryptor('decrypt',$_REQUEST['eId'])."' AND id_shop = '".$_SESSION['shop']."' ";
						$resChk=mysqli_query($conn,$chkSql);

						if(mysqli_num_rows($resChk) > 0){
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

                             
							

				<div class="form-group">

                  <label for="status">Status</label>

                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active

				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive

				 <?php echo $err_status;?>

                </div>

							          

              </div>

              <!-- /.box-body -->	

			 <div class="box-footer">                                       

				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >

				&nbsp;&nbsp;&nbsp;&nbsp;

			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageState.php"); '>

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





