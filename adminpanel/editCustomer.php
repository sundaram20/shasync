<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');

/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['eId'] == ''){ header("location:editCompany.php"); }

/////////////////////////////////////////////////////////////////////////////////////

//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){		

	$err = 0;

	/*if(empty($_POST['email'])){

		$err++;

		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter email id.</font>';

	}elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

		$err++;

		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter valid email id.</font>';

	}else if($db->num_rows2(selectSql(TBL_CUSTOMER,"WHERE `id_customer` NOT IN('".addslashes(encryptor('decrypt',$_POST[id]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."'  and type='2' AND `email` = '".addslashes($_POST['email'])."'",''))){

		$err++;

		$err_email = '<font style="color:red;font-weight:normal;" >Email all-ready exists in our database.</font>';

	}*/

	

	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add

			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');

			$addSql = "   	INSERT INTO `".TBL_CUSTOMER."` SET 

							`id_shop_group` = '1',

							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`id_default_group` = '".addslashes($_POST['id_default_group'])."',

							`id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',

							`id_company_person` = '0',
							`title`='".trim($_REQUEST['title'])."',

							`first_name` = '".trim(addslashes($_POST['first_name']))."',

							`last_name` = '".trim(addslashes($_POST['last_name']))."',

							`designation` = '".trim(addslashes($_POST['designation']))."',

							`grade` = '".trim(addslashes($_POST['grade']))."',

							`email` = '".trim(addslashes($_POST['email']))."',

							`dob` = '".addslashes(date('Y-m-d',strtotime($_POST['dob'])))."',

							`doa` = '".addslashes(date('Y-m-d',strtotime($_POST['doa'])))."',

							`id_country` = '".addslashes($_POST['id_country'])."',

							`id_state` = '".addslashes($_POST['id_state'])."',

							`postcode` = '".addslashes($_POST['postcode'])."',

							`city` = '".trim(addslashes($_POST['city']))."',

							`other_state` = '".trim(addslashes($_POST['other_state']))."',

							`address` = '".trim(addslashes($_POST['address']))."',

							`phone` = '".trim(addslashes($_POST['phone']))."',

							`mobile` = '".trim(addslashes($_POST['mobile']))."',

							`type` = '2'";

			

			$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							,`status` = '".addslashes($_POST['status'])."'";

							

			if(executeSql($addSql)){

				unset($_POST);

				$_SESSION['successMsg'] = 'New contact details has been added sucessfully.';

				header("location:manageCustomer.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'New Contact details has not been saved. Please make corrections below.';

			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update

		

			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'update');

			$editSql = "   	UPDATE `".TBL_CUSTOMER."` SET 

							`id_shop_group` = '1',
							`title`='".trim($_REQUEST['title'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`id_default_group` = '".addslashes($_POST['id_default_group'])."',

							`id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',

							`id_company_person` = '0',

							`first_name` = '".addslashes($_POST['first_name'])."',

							`last_name` = '".addslashes($_POST['last_name'])."',

							`designation` = '".addslashes($_POST['designation'])."',

							`grade` = '".addslashes($_POST['grade'])."',

							`email` = '".addslashes($_POST['email'])."',

							`dob` = '".addslashes(date('Y-m-d',strtotime($_POST['dob'])))."',

							`doa` = '".addslashes(date('Y-m-d',strtotime($_POST['doa'])))."',

							`id_country` = '".addslashes($_POST['id_country'])."',

							`id_state` = '".addslashes($_POST['id_state'])."',

							`postcode` = '".addslashes($_POST['postcode'])."',

							`city` = '".addslashes($_POST['city'])."',

							`other_state` = '".addslashes($_POST['other_state'])."',

							`address` = '".addslashes($_POST['address'])."',

							`phone` = '".addslashes($_POST['phone'])."',

							`mobile` = '".addslashes($_POST['mobile'])."',

							`type` = '2'";

			

			$editSql .= "	,`last_modified` = '".currenDateTime()."'

							,`status` = '".addslashes($_POST['status'])."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_POST['id']))."'";								

			if(executeSql($editSql)){

				

				$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor('decrypt',$_POST['id'])."'").' details has been updated sucessfully.';

				header("location:manageCustomer.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor('decrypt',$_POST['id'])."'").' details has not been saved.Please make corrections below.';

			}

		}

	}else{//Error

		$err++;

		$_SESSION['errorMsg'] = 'Contact details has not been saved. Please make corrections.';

	}

}

// ----------cate---------

if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_CUSTOMER."`

								WHERE `id_customer` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."' and type='2'";

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

        <small>Manage Contacts</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Contacts</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

      <div class="row">

        <!-- left column -->

        <div class="col-md-12">

          <!-- general form elements -->

           <div class="nav-tabs-custom">

			<ul class="nav nav-tabs">

			   <li ><a href="editCompany.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li> 

			  <li class="active"><a href="manageCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Contacts</a></li>           

			   

            </ul> 

            <div class="box-header with-border">

               <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Contacts : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes($_REQUEST['eId'])."'"); ?></a></h3>    

			   <a href="manageCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  

			</div> 

            <!-- /.box-header -->

            <!-- form start -->  			        

			  <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" id="form1">

                <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />

				<input type="hidden" value="<?php echo selectColumn(TBL_COMPANY,'id_default_group'," WHERE `id_company` = '".encryptor('decrypt',$_REQUEST['eId'])."'");?>" name="id_default_group" />

					<div class="form-group has-error" align="center">

						<?php if($_SESSION['errorMsg']){?>

						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

						<?php unset($_SESSION['successMsg']);}?>

					 </div>

              <div class="box-body">

				<div class="row">	

				<div class="form-group col-sm-2">

                  <label for="title">Title<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Mr. Or Mrs." id="title" name="title" value="<?php if($_POST) echo $_POST['title'];else echo stripslashes($row->title);?>" data-parsley-required >

				<?php echo $err_first_name;?>

                </div> 		  

			     <div class="form-group col-sm-3">

                  <label for="first_name">First Name<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter First name" id="first_name" name="first_name" value="<?php if($_POST) echo $_POST['first_name'];else echo stripslashes($row->first_name);?>" data-parsley-required >

				<?php echo $err_first_name;?>

                </div>                

				 <div class="form-group col-sm-3">

                  <label for="last_name">Last Name<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter Last name" id="last_name" name="last_name" value="<?php if($_POST) echo $_POST['last_name'];else echo stripslashes($row->last_name);?>" data-parsley-required >

				<?php echo $err_last_name;?>

                </div>	

				

				<!--<div class="form-group col-sm-4">

                  <label for="designation">Designation</label>
                  <?php $designation = selectColumn(TBL_DESIGNATION_MASTER,'name','where id ="'.$row->designation.'" ') ?>
                  <input type="text" class="form-control" placeholder="Enter designation" id="designation" name="designation" value="<?php if($_POST) echo $_POST['designation'];else echo stripslashes($designation);?>" >

				<?php echo $err_designation;?>

                </div>-->

                <div class="form-group col-sm-4">
                  <label for="name">Designation<font color="#FF0000">*</font></label>
                 <?php $categoryDropDown = '<select class="form-control select2" name="designation" data-parsley-required>
						<option value="">Select Designation</option>';
						  $resCat = selectSql(TBL_DESIGNATION_MASTER,"where id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
							  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
								if($_REQUEST['designation'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->designation == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
								}
							 }
				echo $categoryDropDown .= '</select>';
											  ?>
				<?php echo $err_designation;?>
                </div>

				

							

				 

			   </div>	

				<div class="row">

				<div class="form-group col-sm-4">

                  <label for="">Grade</label>

                  <input type="text" class="form-control" placeholder="Enter grade" id="grade" name="grade" value="<?php if($_POST) echo $_POST['grade'];else echo stripslashes($row->grade);?>" >

				<?php echo $err_grade;?>

                </div>

				<div class="form-group col-sm-4">

                  <label for="email">Email Id</label>

                  <input type="text" class="form-control" placeholder="Enter email id" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>" data-parsley-type="email"  >

				<?php echo $err_email;?>

                </div>

					<div class="form-group  col-sm-4">

					  <label for="phone">Phone Number</label>

					  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($row->phone);?>" data-parsley-type="digits">

					<?php echo $err_phone;?>

					</div>

					

				  </div>				

			    <div class="row">	

			  	<div class="form-group  col-sm-4">

					  <label for="mobile">Mobile Number</label>

					  <input type="text" class="form-control" placeholder="Enter mobile number" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($row->mobile);?>" data-parsley-type="digits" data-parsley-length="[10, 10]" >

					<?php echo $err_mobile;?>

					</div>				

					<div class="form-group col-sm-4">

                  <label for="address">Address</label>

				   <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address" ><?php if($_POST) echo $_POST['address'];else echo stripslashes($row->address);?></textarea>

				<?php echo $err_address;?>

                </div>		  

			   

				<div class="form-group col-sm-4">

                  <label for="city">City</label>

                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>" >

				<?php echo $err_city;?>

                </div>

				

			  </div>

	

			 	<div class="row">	

				 <div class="form-group col-sm-4">

						<label for="id_country" >Country</label>  

								<select class="form-control select2" name="id_country" id="id_country" data-parsley-errors-container="#countryError" onchange="getState(this.value,'','');" >

									<option value="">Select Country</option>

									<?php 

									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

												  if(num_rows($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

														if($_REQUEST['id_country'] == $resultCat->id_country){

															$selected = 'selected="selected"';

														}elseif($row->id_country == $resultCat->id_country){

														$selected = 'selected="selected"';

														}else{

															$selected = '';

														}

														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';

													}

												  }

												  echo $countryDropDown;

									

									 ?>

								</select>

							  <span id="countryError"></span>

							</div>									

				<div class="form-group col-sm-4">

					 <label for="id_state">State</label> 

						<div id="state"> 

						 <select class="form-control"  name="id_state" id="id_state"  >

							<option value="">Please Select State</option>								

						</select>

						

						</div>

					  <span id="#stateError"></span>

					</div>

				<div class="form-group col-sm-4">

                  <label for="postcode">Pincode</label>

                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo stripslashes($row->postcode);?>">

				<?php echo $err_postcode;?>

                </div>

				

				

			  </div>			  

				<div class="row">

				<div class="form-group col-sm-4">

                  <label for="dob">Date of Birth</label>

                  <input type="text" class="form-control pickerdate" placeholder="Enter date of birth" id="dob" name="dob" value="<?php if($_POST) echo $_POST['dob'];else if($row->dob) echo stripslashes(date('d-m-Y',strtotime($row->dob))); else echo date('d-m-Y') ;?>" data-parsley-required>

				<?php echo $err_dob;?>

                </div>



                <div class="form-group col-sm-4">

                  <label for="doa">Date of Anniversary</label>

                  <input type="text" class="form-control pickerdate" placeholder="Enter date of Anniversary" id="doa" name="doa" value="<?php if($_POST) echo $_POST['doa'];else if($row->doa) echo stripslashes(date('d-m-Y',strtotime($row->doa))); else echo date('d-m-Y') ;?>">

				<?php echo $err_dob;?>

                </div>

				

				

				<div class="form-group col-sm-4" style="margin-top:30px;">

                  <label for="status">Status </label>

                 <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active 

				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive

				 <?php echo $err_status;?>

                </div>

				</div>

		

				<?php if($row->date_created){?>

				<div class="row">  

				<div class="form-group col-sm-4">

                  <label for="date_created">Date Created</label>

                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				

                </div> 

				

				<div class="form-group col-sm-4">

                  <label for="last_modified">Last Updated</label>

                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				

                </div> 

				

				<div class="form-group col-sm-4">

                  <label for="last_modified_by">Last Updated By</label>

				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>

                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				

                </div>  

				 </div> 

				  <?php } ?>            

              </div>

              <!-- /.box-body -->	

			 <div class="box-footer">                                       

				<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >

				&nbsp;&nbsp;&nbsp;&nbsp;

			   <a class="btn btn-default" href='manageCompanyCustomer.php'>Cancel</a>

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

    <script>

  window.onload = function() { getState(<?php if($_REQUEST['id_country']){echo "'".$_REQUEST['id_country']."'";}elseif($row->id_country != ''){echo "'".$row->id_country."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['id_state']){echo "'".$_REQUEST['id_state']."'";}elseif($row->id_state != ''){echo "'".$row->id_state."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['other_state'] != ''){echo "'".$_REQUEST['other_state']."'";}elseif($row->other_state != ''){echo "'".$row->other_state."'";}else { echo "'"."'";} ?>); };





  

  </script>						

<?php include_once("includes/footer.php")?>



<!--<script type="text/javascript">

	$("document").ready(function(){

		var state_val = $("#id_state").val();

		console.log(state_val);

		

			

		/*if(state_val == ""){

			$("#form1").submit(function(e){

				$("#errorTxt").HTML('Kindly select the state');

				return false;



			});

		}*/

	});

</script>-->





