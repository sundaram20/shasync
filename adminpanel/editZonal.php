<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_ZONAL,'view');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

$image_path = $UPLOAD_FILES.'/users/';

$image_display_path = $UPLOAD_FILES_PATH ."/users/";

//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){

	$err = 0;	

	if(empty($_POST['name'])){

		$err++;

		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';

	}else if($db->num_rows2(selectSql(TBL_ZONAL,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes($_POST['name'])."'",''))){

		$err++;

		$err_name = '<font style="color:red;font-weight:normal;" ><br>Zone name all-ready exists in our database.</font>';

	}

	

	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			checkUserLevelPermission($_SESSION['userLevel'],TBL_ZONAL,'add');

			$addSql = "   	INSERT INTO `".TBL_ZONAL."` SET 

							`name` = '".addslashes($_POST['name'])."',

							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`ids_state` = '".addslashes(implode(',',$_POST['state']))."',

							`order_list_number` = '".addslashes($_POST['order_list_number'])."',

							`id_shop_group` = '1'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							,`status` = '".addslashes($_POST['status'])."'";

			if(executeSql($addSql)){

				unset($_POST);

				$_SESSION['successMsg'] = 'New Zone details has been added sucessfully.';

				header("location:manageZonal.php");

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'Zone has not been saved. Please make corrections below.';

			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update

			checkUserLevelPermission($_SESSION['userLevel'],TBL_ZONAL,'update');

			$editSql = "   	UPDATE `".TBL_ZONAL."` SET 

							`name` = '".addslashes($_POST['name'])."',

							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`ids_state` = '".addslashes(implode(',',$_POST['state']))."',

							`order_list_number` = '".addslashes($_POST['order_list_number'])."',

							`id_shop_group` = '1'";

			$editSql .= "	,`last_modified` = '".currenDateTime()."'

							,`status` = '".addslashes($_POST['status'])."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";

								

			if(executeSql($editSql)){

				$_SESSION['successMsg'] = selectColumn(TBL_ZONAL,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has been updated sucessfully.';

				header("location:manageZonal.php?&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = selectColumn(TBL_ZONAL,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'").' details has not been saved.Please make corrections below.';

			}

		}

	}else{//Error

		$err++;

		$_SESSION['errorMsg'] = 'Zone details has not been saved.Please make corrections.';

	}

}

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = " SELECT * FROM `".TBL_ZONAL."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

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

        <small>Zone Master</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Zone Master</li>

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

              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Zone</h3>

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

                  <label for="name">Zone Name<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter Zone name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>

				<?php echo $err_name;?>

                </div>

                <!--<div class="form-group">
                  <label for="state">State<font style="color:red;">*</font></label>
                  
				  <select required="required" class="form-control select2" name="state[]" multiple="multiple">				  
                  <?php 
					$sqlUserSql = "SELECT * FROM ".TBL_STATE." WHERE status='1' AND id_country='110' ";
					$sqlUserActions = mysqli_query($conn,$sqlUserSql);
					$iCounterActions = 0;
					while($resUserActions = mysqli_fetch_object($sqlUserActions)){
						$chkSql = "SELECT * FROM `".TBL_ZONAL."` WHERE FIND_IN_SET('".$resUserActions->id_state."',ids_state )  ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserActions->id_state.'">'.$resUserActions->name.'</option>';
						
						$iCounterActions++;
					}
					?>
					</select>
                    <?php echo $err_hotel_services;?>
                </div>-->

                
                

                <div class="form-group">

                  <label for="order_list_number">Order List<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter Order List" id="order_list_number" name="order_list_number" value="<?php if($_POST) echo $_POST['order_list_number'];else echo stripslashes($row->order_list_number);?>" data-parsley-required >

				<?php echo $err_order_list_number;?>

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

			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageZonal.php"); '>

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





